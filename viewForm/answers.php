<style>
table, td, th {
    border: 1px solid black;
    border-collapse: collapse;
    padding: 2px;
}
</style>

<?php

include '../vars.php';

if (getip() !== false)
    $clientip = getip();
else
    throwerror("Couldn't get your IP.");

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

$a = json_decode($row["form_a"]);
$q = json_decode($row["form_q"]);

if (!$a)
    throwerror("There are no answers");

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
