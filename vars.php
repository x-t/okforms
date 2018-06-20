<?php

include 'settings.php';

/*
 * throwerror($e)
 *
 * Throws $error and stops PHP execution
 * 
 * ! For legacy reasons only. Use logger() or genErrPage()
 *
 * @param str $e  Error to throw
 */
function throwerror($e) {
    $x = new stdClass();
    $x->type = "legacy_error";
    $x->desc = $e;
    genErrPage($x);
}


/*
 * genErrPage($e)
 * 
 * Generate an error page
 * 
 * @param stdClass $e  The error
 */
function genErrPage($e) {
    global $SQLTB;

    /*
     * $e info
     * @req $e->type Error type
     * @req $e->desc Error description
     * @opt $e->id   Error log ID
     */

    $x = new stdClass();
    if ($e->type == "success_vote" || $e->type == "success_report")
        $x->mainMsg = '<span class="fas fa-check-circle"></span> Success';
    else
        $x->mainMsg = '<span class="fas fa-exclamation-circle"></span> Failure';
    echo '<script src="/js/styleSheetMan.min.js"></script>';
    echo '<script>delAllStyles();</script>';
    echo "<script>addStyle('/css/errPage.css');</script>";
    echo "<script>document.body.innerHTML = '';</script>";
    echo '<div class="errMain c">';
        printf("<h2>%s</h2>", $x->mainMsg);
        printf("<p>%s</p>", $e->desc);
        if ($e->id) {
            echo '<br />';
            printf("<small class=\"eid\">Log ID %d</small>", $e->id);
        }
        echo '<table>';
            echo '<tr>';
                echo '<td><a class="button" href="/"><span class="fas fa-home"></span> Home</a></td>';
                echo '<td><a class="button" href="javascript:window.history.back()"><span class="fas fa-arrow-circle-left"></span> Back</td>';
                if ($e->type == "success_vote") {
                    $p = preg_split('/\s+/', $e->desc);
                    $id = end($p);
                    $sql = sqlcon();
                    $r = $sql->query("SELECT * FROM {$SQLTB} WHERE form_id='" . $sql->real_escape_string($id) . "'");
                    if (!$r) {
                        printf("Fatal SQL error. %s", $sql->error);
                    }
                    $r = $r->fetch_assoc();
                    if ($r["form_type"] == "poll")
                        printf('<td><a class="button" href="/viewForm/answers.php?id=%s"><span class="fas fa-chart-pie"></span> View answers</a></td>', $id);
                } else if ($e->type == "form_expire") {
                    printf('<td><a class="button" href="/viewForm/answers.php?id=%s"><span class="fas fa-chart-pie"></span> View answers</a></td>', $id);
                }
            echo '</tr>';
        echo '</table>';
    echo '</div>';
    if ($e->type == "timeout"):
    ?>
        <script src="/js/countdown.min.js"></script>
        <script>
            countdown(document.getElementById("timeoutTime").innerHTML, function(s) {
                document.getElementById("timeoutTime").innerHTML = s;
            }, function() {
                location.reload(true);
            });
        </script>
    <?php endif;
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

    $x = new stdClass();
    $sql = sqlcon();
    $ip = $sql->real_escape_string($ip);
    $desc_nonsql = $desc;
    $desc = $sql->real_escape_string($desc);
    $err_time = time();
    if (strlen($desc) > 16777215)
        throwerror("Yeah that's a loooong description.");
    $q = "INSERT INTO {$SQLLOGTB}(type, ip, time, descr) VALUES('{$type}', '{$ip}', '{$err_time}', '{$desc}')";
    $r = $sql->query($q);
    if (!$r)
        throwerror("SQL error " . $sql->error);

    if ($type != "success_new") {
        $x->type = $type;
        $x->desc = $desc_nonsql;
        $x->id = $sql->insert_id;
        genErrPage($x);
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
 * @return stdClass Can do?/Why not
 */
function can_do($ip, $thing) {
    global $SQLLOGTB;
    global $SQLBANTB;
    global $RATELIMIT_NEW;
    global $RATELIMIT_VOTE;
    
    $x = new stdClass();
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
            $x->can = false;
            $x->why = "ban";
            $x->reason = $row["ban_reason"];
            return $x;
        }

        if ($row["ban_vote"] == 1 && $thing == "vote") {
            $x->can = false;
            $x->why = "ban";
            $x->reason = $row["ban_reason"];
            return $x;
        }
    }
    // Rate limit check
    if ($thing == "vote") {
        $q = "SELECT * FROM {$SQLLOGTB} WHERE ip='{$ip}' AND type='success_vote' ORDER BY ID DESC LIMIT 1";
        $r = $sql->query($q);
        if (!$r)
            logger($ip, "sql_error", "SQL error " . $sql->error);
        if ($r->num_rows == 0) {
            $x->can = true;
            return $x;
        } else {
            $row = $r->fetch_assoc();
            $dif = $curtime - $row["time"];
            if ($dif < $RATELIMIT_VOTE) {
                $x->can = false;
                $x->why = "timeout";
                $x->timeout = $RATELIMIT_VOTE - $dif;
                return $x;
            } else {
                $x->can = true;
                return $x;
            }
        }
    } else if ($thing == "create") {
        $q = "SELECT * FROM {$SQLLOGTB} WHERE ip='{$ip}' AND type='success_new' ORDER BY ID DESC LIMIT 1";
        $r = $sql->query($q);
        if (!$r)
            logger($ip, "sql_error", "SQL error " . $sql->error);
        if ($r->num_rows == 0) {
            return true;
        } else {
            $row = $r->fetch_assoc();
            $dif = $curtime - $row["time"];
            if ($dif < $RATELIMIT_NEW) {
                $x->can = false;
                $x->why = "timeout";
                $x->timeout = $RATELIMIT_NEW - $dif;
                return $x;
            } else {
                $x->can = true;
                return $x;
            }
        }
    }

    $sql->close();
    return false;
}

?>
