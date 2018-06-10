<?php

include '../vars.php';

if (getip() !== false)
    $clientip = getip();
else
    throwerror("Couldn't get your IP.");

if (!isset($_POST["form_id"]) || !$_POST["form_id"])
    logger($clientip, "form_id_404", "Form ID was not sent or is empty.");
if (!isset($_POST["reason"]) || !$_POST["reason"])
    logger($clientip, "report_reason_404", "Report reason was not sent or is empty");

len_or_error($_POST["reason"], $MAX_REPORT_REASON_LEN, "Reason was too large &gt; {$MAX_REPORT_REASON_LEN} chars");

$sql = sqlcon();

$reason = $sql->real_escape_string($_POST["reason"]);
$ip = $sql->real_escape_string($clientip);
$form_id = $sql->real_escape_string($_POST["form_id"]);

$q = "SELECT * FROM {$SQLREPORTTB} WHERE form_id='{$form_id}' AND ip='{$ip}'";
$r = $sql->query($q);
if (!$r)
    logger($clientip, "sql_error", "SQL Error " . $sql->error);
if ($r->num_rows != 0)
    logger($clientip, "form_already_reported", "You already reported this form. ({$form_id})");

$q = "SELECT * FROM {$SQLTB} WHERE form_id='" . $sql->real_escape_string($_POST["form_id"]) . "'";
$r = $sql->query($q);
if (!$r)
    logger($clientip, "sql_error", "SQL Error " . $sql->error);
if ($r->num_rows == 0)
    logger($clientip, "form_404", "Form ID doesn't exist");

$q = "INSERT INTO {$SQLREPORTTB}(ip, form_id, reason) VALUES('{$ip}', '{$form_id}', '{$reason}')";
$r = $sql->query($q);
if (!$r)
    logger($clientip, "sql_error", "SQL error " . $sql->error);

logger($clientip, "success_report", "Successfully reported form {$form_id}");

?>