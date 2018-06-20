<?php

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

?>