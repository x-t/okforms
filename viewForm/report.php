<?php include '../vars.php'; ?>
<html>
<head>
<link id="fontawesome" rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous" />
<link rel="stylesheet" type="text/css" href="/css/reportForm.min.css" />
</head>
<body>
<?php

if (getip() !== false)
    $clientip = getip();
else
    throwerror("Couldn't get your IP.");

?>

<?php
if (!isset($_GET["id"]) || !$_GET["id"]) {
    if (!isset($_POST["form_id"]) || !$_POST["form_id"])
        logger($clientip, "form_id_404", "Form ID was not sent or is empty.");
    if (!isset($_POST["reason"]) || !$_POST["reason"])
        logger($clientip, "report_reason_404", "Report reason was not sent or is empty");
}

if (isset($_POST["form_id"])) {
    goto post_block;
}

$sql = sqlcon();

$ip = $sql->real_escape_string($clientip);
$form_id = $sql->real_escape_string($_GET["id"]);

$q = "SELECT * FROM {$SQLREPORTTB} WHERE form_id='{$form_id}' AND ip='{$ip}'";
$r = $sql->query($q);
if (!$r)
    logger($clientip, "sql_error", "SQL Error " . $sql->error);
if ($r->num_rows != 0)
    logger($clientip, "form_already_reported", "You already reported this form. ({$form_id})");

$q = "SELECT * FROM {$SQLTB} WHERE form_id='{$form_id}'";
$r = $sql->query($q);
if (!$r)
    logger($clientip, "sql_error", "SQL Error " . $sql->error);
if ($r->num_rows == 0)
    logger($clientip, "form_404", "Form ID doesn't exist");

?>
<div class="mainReport">
<h3 class="c">Report form</h3>
<form id="reportForm" method="post" action="/viewForm/report.php">
<input type="hidden" name="form_id" value="<?php echo htmlspecialchars($_GET["id"]) ?>" />
<textarea form="reportForm" name="reason" placeholder="Explain why you want to report this form (Max length: <?php echo $MAX_REPORT_REASON_LEN; ?>)" maxlength="<?php echo $MAX_REPORT_REASON_LEN; ?>"></textarea>
<button type="submit" class="submitBtn">Submit report</button>
</form>
</div>

<?php
exit();

post_block:
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
</body>
</html>