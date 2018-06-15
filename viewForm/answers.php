<?php include '../vars.php'; ?>
<html>
<head>
<link id="fontawesome" rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous" />
<link rel="stylesheet" type="text/css" href="/css/answersPage.min.css" />
</head>
<body>
<?php

if (getip() !== false)
    $clientip = getip();
else
	throwerror("Couldn't get your IP.");

if (!isset($_GET["id"]) || !$_GET["id"]) {
	if (!isset($_POST["form_id"]) || !$_POST["form_id"])
		logger($clientip, "form_id_404", "Form ID was not sent or is empty.");
	if (!isset($_POST["password"]) || !$_POST["password"])
		logger($clientip, "report_reason_404", "Password was not sent or is empty");
}

if (isset($_POST["form_id"])) {
	goto post_block;
}

$sql = sqlcon();

$q = "SELECT * FROM {$SQLTB} WHERE form_id='" . $sql->real_escape_string($_GET["id"]) . "'";
$r = $sql->query($q);
if (!$r){
	logger($clientip, "sql_err", "SQL error " . $sql->error);
}
if ($r->num_rows == 0) {
	logger($clientip, "form_404", "Form doesn't exist");
}
$row = $r->fetch_assoc();
if ($row["form_type"] == "poll") {
	goto poll_block;
}
?>
<div class="mainForm">
<h3 class="c">View answers</h3>
<form id="reportForm" method="post" action="/viewForm/answers.php">
<input type="hidden" name="form_id" value="<?php echo htmlspecialchars($_GET["id"]) ?>" />
<div class="c"><input type="password" name="password" placeholder="Form password" />
<button type="submit" class="submitBtn">View answers</button></div>
</form>
</div>

<?php
exit();

post_block: 

$sql = sqlcon();

$q = "SELECT * FROM {$SQLTB} WHERE form_id='" . $sql->real_escape_string($_POST["form_id"]) . "'";
$r = $sql->query($q);
if (!$r)
	logger($clientip, "sql_err", "SQL error " . $sql->error);
if ($r->num_rows == 0)
	logger($clientip, "form_404", "Form doesn't exist");
$row = $r->fetch_assoc();
if ($row["form_type"] == "form")
	if ($row["form_pass"] !== hash('sha256', $_POST["password"]))
		logger($clientip, "password_err", "Incorrect password.");

poll_block:

$a = json_decode($row["form_a"]);
$q = json_decode($row["form_q"]);

if (!$a)
	throwerror("There are no answers");

?>

<?php
$p = array();
foreach ($a as $z)
    array_push($p, $z->ip);
$p = array_count_values($p);
$p1 = array();
$i = 1;
foreach ($p as $key => $value) {
    $p1[$key] = $i;
    $i++;
}

echo '<table>';
echo '<tr>';
echo '<th>Person</th>';
echo '<th>Time</th>';
foreach ($q as $x) {
	printf('<th>%s</th>', htmlspecialchars($x->q));
}
echo '</tr>';
foreach ($a as $x) {
	echo '<tr>';
	printf('<td>#%s</td>', htmlspecialchars($p1[$x->ip]));
	printf('<td>%s</td>', htmlspecialchars(gmdate("Y-m-d H:i:s", $x->time)));
	foreach($x->key as $y) {
		printf('<td>%s</td>', htmlspecialchars($y->a));
	}
	echo '</tr>';
}
echo '</table>';

$sql->close();

?>

</html>
