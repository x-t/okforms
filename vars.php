<?php

/**********************************************
 * Variables and functions for the application
 **********************************************/

$ADMIN_EMAIL = "sysadmin@example.com";   // Contact info, could be anything, email as an example.

$RATELIMIT_NEW = "600";             // Rate limit for how frequently an user can create a new form (seconds)
$RATELIMIT_VOTE = "120";            // Rate limit for how frequently an user can answer forms (seconds)

/**************
 * MySQL data
 **************/
$SQLUSER = "form_hand";             // MySQL user
$SQLPASS = "pass";                  // MySQL password
$SQLSERVER = "127.0.0.1";           // MySQL server
$SQLDB = "forms";                   // MySQL database
$SQLTB = "form";                    // MySQL table for tables
$SQLLOGTB = "logger";               // MySQL table for logs
$SQLBANTB = "bans";                 // MySQL table for bans
$SQLREPORTTB = "reports";           // MySQL table for reports

$MAX_CHOICE_LEN = 512;          // Max choice length
$MAX_QUESTION_LEN = 512;        // Max question length
$MAX_TITLE_LEN = 128;           // Max title length
$MAX_DESC_LEN = 512;            // Max description length
$MAX_PASS_LEN = 64;             // Max password length
$MAX_TANSWER_LEN = 4;           // Max text answer length, 9*N (9999)
$MAX_REPORT_REASON_LEN = 2048;  // Max report reason length

/******************************
 * Functions! Yay!
 ******************************/

/*
 * throwerror($e)
 *
 * Throws $error and stops PHP execution
 *
 * @param str $e  Error to throw
 */
function throwerror($e) {
    echo '<p id="error">' . $e . '</p><br /><a href="javascript:window.history.back()">Back</a>';
    error_log($e);
    exit();
}

/*
 * len_or_error($s, $l, $e)
 *
 * If $s length is over $l, throw $e
 *
 * @param str $s String to test
 * @param int $l Length to test
 * @param str $e Error to throw
 */
function len_or_error($s, $l, $e) {
    if (strlen($s) > $l)
        logger("?", "len_or_error", $e);
}

/*
 * gen64($l)
 *
 * Generate an $l long string that uses base64url
 *
 * @param int $l Length to generate
 * 
 * @return str Pseudo-random base64URL string
 */
function gen64($l) {
    $index = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_';
    $res = '';
    for ($x = 1; $x <= $l; $x++) {
        $res .= $index[rand(1, strlen($index) - 1)];
    }

    return $res;
}

/*
 * sqlcon()
 * 
 * Make an SQL connection
 * 
 * @return mysqli (obj) MySQLi object/connection
 */
function sqlcon() {
    global $SQLUSER;
    global $SQLPASS;
    global $SQLSERVER;
    global $SQLDB;

    $sql = new mysqli($SQLSERVER, $SQLUSER, $SQLPASS, $SQLDB);
    if (!$sql) {
        throwerror("SQL had an error while creating a connection");
    }

    return $sql;
}

/*
 * getip()
 *
 * Get the client's IP
 *
 * @return str Client's IP if found
 * @return bool false if not found
 */
function getip() {
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ip = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ip = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
       $ip = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ip = $_SERVER['REMOTE_ADDR'];
    else
        $ip = false;

    return $ip;
}

/*
 * logger($ip, $type, $desc)
 * 
 * Add a log to $SQLLOGTB
 * 
 * @param str $ip Client's IP
 * @param str $type Type of log entry
 * @param str $desc Description
 */
function logger($ip, $type, $desc) {
    global $SQLLOGTB;

    $sql = sqlcon();
    $ip = $sql->real_escape_string($ip);
    $desc = $sql->real_escape_string($desc);
    $err_time = time();
    if (strlen($desc) > 16777215)
        throwerror("Yeah that's a loooong description.");
    $q = "INSERT INTO {$SQLLOGTB}(type, ip, time, descr) VALUES('{$type}', '{$ip}', '{$err_time}', '{$desc}')";
    $r = $sql->query($q);
    if (!$r)
        throwerror("SQL error " . $sql->error);

    if ($type != "success_new") {
        if ($type != "success_vote")
            throwerror("Log ID #" . $sql->insert_id . ": {$desc}");
    }

    $sql->close();
}

/*
 * can_do($ip, $thing)
 * 
 * Check if the client identified with $ip can do $thing
 * 
 * @param str $ip Client's IP
 * @param str $thing Thing to do
 * 
 * @return bool true if $ip can do $thing, false if it can't
 */
function can_do($ip, $thing) {
    global $SQLLOGTB;
    global $SQLBANTB;
    global $RATELIMIT_NEW;
    global $RATELIMIT_VOTE;
    
    $sql = sqlcon();
    $ip = $sql->real_escape_string($ip);
    $curtime = time();
    // Ban checking
    $q = "SELECT * FROM {$SQLBANTB} WHERE ban_ip='{$ip}'";
    $r = $sql->query($q);
    if (!$r)
        throwerror("SQL error " . $sql->error);
    if ($r->num_rows != 0) {
        $row = $r->fetch_assoc();
        if ($row["ban_create"] == 1 && $thing == "create") {
            echo "<p id=\"error\">You are banned. Reason: " . $row["ban_reason"] . "</p>";
            return false;
        }

        if ($row["ban_vote"] == 1 && $thing == "vote") {
            echo "<p id=\"error\">You are banned. Reason: " . $row["ban_reason"] . "</p>";
            return false;
        }
    }
    // Rate limit check
    if ($thing == "vote") {
        $q = "SELECT * FROM {$SQLLOGTB} WHERE ip='{$ip}' AND type='success_vote' ORDER BY ID DESC LIMIT 1";
        $r = $sql->query($q);
        if (!$r)
            throwerror("SQL error " . $sql->error);
        if ($r->num_rows == 0) {
            return true;
        } else {
            $row = $r->fetch_assoc();
            $dif = $curtime - $row["time"];
            if ($dif < $RATELIMIT_VOTE) {
                printf("<p id='error'>Please wait %d seconds before voting again.</p>", $RATELIMIT_VOTE - $dif);
                return false;
            } else {
                return true;
            }
        }
    } else if ($thing == "create") {
        $q = "SELECT * FROM {$SQLLOGTB} WHERE ip='{$ip}' AND type='success_new' ORDER BY ID DESC LIMIT 1";
        $r = $sql->query($q);
        if (!$r)
            throwerror("SQL error " . $sql->error);
        if ($r->num_rows == 0) {
            return true;
        } else {
            $row = $r->fetch_assoc();
            $dif = $curtime - $row["time"];
            if ($dif < $RATELIMIT_NEW) {
                printf("<p id='error'>Please wait %d seconds before making a new form.</p>", $RATELIMIT_NEW - $dif);
                return false;
            } else {
                return true;
            }
        }
    }

    $sql->close();
    return false;   // Every check failed? Something's off...
}

?>
