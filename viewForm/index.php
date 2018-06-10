<?php include '../vars.php'; ?>
<html>
<head>
<title>viewing form</title>
<script>
var MAX_P_LEN = <?php echo $MAX_PASS_LEN; ?>;
var MAX_R_LEN = <?php echo $MAX_REPORT_REASON_LEN; ?>;
</script>
<script src="/js/viewForm.min.js"></script>
</head>
<body>
<?php
if (getip() !== false)
    $clientip = getip();
else
    throwerror("Couldn't get your IP.");

if (!isset($_GET['id']) || !$_GET['id'])
    throwerror("No ID given.");

$sql = sqlcon();

$q = "SELECT * FROM {$SQLTB} WHERE form_id='" . $sql->real_escape_string($_GET['id']) . "'";
$r = $sql->query($q);

if ($r->num_rows == 0)
    logger($clientip, "404", "Form doesn't exist.");

$row = $r->fetch_assoc();

if (time() > $row["form_expires"]) {
    if ($row["form_type"] == "form") {
?>
<p id="answers">
<a href="javascript:admin_creds('<?php echo $row["form_id"]; ?>')">View form answers</a>
</p>
<?php
    } else {
?>
<p id="answers">
<a href="javascript:document.getElementById('answers_form').submit()">View poll answers</a>
<form id="answers_form" method="post" action="/viewForm/answers.php">
<input type="hidden" value="<?php echo htmlspecialchars($_GET["id"]); ?>" name="form_id" />
</form>
</p>
<?php
    }
    throwerror("Viewing an expired form. (" . $row["form_id"] . ")");
}

echo "<h3>" . htmlspecialchars($row['form_title']) . "</h3>";
if ($row["form_desc"])
    echo "<p><em>" . htmlspecialchars($row['form_desc']) . "</em></p>";
$qs = json_decode($row["form_q"]);

echo '<form action="/viewForm/vote.php" method="post" id="vote">';
printf('<input type="hidden" name="form_id" value="%s" />', htmlspecialchars($_GET['id']));
$i = 0;
foreach ($qs as $s) {
    if ($row["form_type"] == "form")
        printf("<p>%d. %s</p>", $i + 1, htmlspecialchars($s->q));
    printf('<input type="hidden" name="k[%d][r]" value="%d" />', $i, $s->req);
    printf('<input type="hidden" name="k[%d][t]" value="%s" />', $i, $s->type);
    if ($s->type == "text") {
        if ($s->maxlen <= 512) {
            printf('<p><input type="text" name="k[%d][a]" maxlength="%d" /></p>', $i, $s->maxlen);
        } else {
            printf('<p><textarea name="k[%d][a]" form="vote" maxlength="%d"></textarea></p>', $i, $s->maxlen);
        }
    } else if ($s->type == "single") {
        foreach ($s->choices as $c) {
            printf('<p><input type="radio" name="k[%d][a]" value="%s" />%s</p>', $i, htmlspecialchars($c), htmlspecialchars($c));
        }
    } else if ($s->type == "multiple") {
        foreach ($s->choices as $c) {
            printf('<p><input type="checkbox" name="k[%d][a][]" value="%s" />%s</p>', $i, htmlspecialchars($c), htmlspecialchars($c));
        }
    }

    $i++;
}

$sql->close();

if (can_do($clientip, "vote"))
    echo '<p id="submit-ans"><input type="submit" value="Submit answers" /></p>';

?>
</form>
<hr />
<?php if ($row["form_type"] == "form") { ?>
<p><a href="javascript:admin_creds(<?php echo '\'' . htmlspecialchars($_GET['id']) . '\''; ?>)">View form answers</a> 
<?php } else { ?>
<p>
<a href="javascript:{}" onclick="document.getElementById('answers_form').submit()">View poll results</a>
<?php } ?>
<a href="javascript:report_form('<?php echo htmlspecialchars($_GET["id"]); ?>')">Report form</a>
</p>
<p id="answers">
<?php 
if ($row["form_type"] == "poll") {
?>
<form id="answers_form" method="post" action="/viewForm/answers.php">
<input type="hidden" value="<?php echo htmlspecialchars($_GET["id"]); ?>" name="form_id" />
</form>
<?php } ?>
</p>
<p id="report"></p>
</body>
</html>
