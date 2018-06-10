<?php

include '../vars.php';

if (getip() !== false)
    $clientip = getip();
else
    throwerror("Couldn't get your IP.");

if (!can_do($clientip, "vote"))
    throwerror("You can't vote");

if (!isset($_POST["form_id"]) || !$_POST["form_id"])
    logger($clientip, "form_id_404", "Form ID was not sent or is empty.");

$sql = sqlcon();

$x = new stdClass();
$x->ip = $clientip;
$x->time = time();

$q = "SELECT * FROM {$SQLTB} WHERE form_id='" . $sql->real_escape_string($_POST["form_id"]) . "'";
$r = $sql->query($q);

if (!$r)
    logger($clientip, "sql_error", "SQL Error " . $sql->error);

if ($r->num_rows == 0)
    logger($clientip, "form_404", "Form ID doesn't exist");

$row = $r->fetch_assoc();
if (time() > $row["form_expires"])
    logger($clientip, "form_expired", "Form expired, you cannot vote!");

if ($row["form_sameip"] == 0) {
    $a = json_decode($row["form_a"]);
    $p = array();
    foreach ($a as $z)
        array_push($p, $z->ip);
    if (in_array($clientip, $p))
        logger($clientip, "already_answered", "You have already answered this form");
}

$qs = json_decode($row["form_q"]);

if (sizeof($qs) !== sizeof($_POST["k"]))
    logger($clientip, "keys_count", "The amount of keys don't match the amount of questions.");

for ($i = 0; $i < sizeof($qs); $i++) {
    $q = $qs[$i];
    $a = $_POST["k"][$i];
    
    if (!isset($a["r"]))
        logger($clientip, "req_404", "req was not found.");
    if (!$a["t"])
        logger($clientip, "type_404", "type was not found.");

    if ($a["t"] !== $q->type)
        logger($clientip, "ans_unmatch", "answer key was not the same type as question");
    if (intval($a["r"]) !== $q->req)
        logger($clientip, "req_unmatch", "requirements don't match");

    if (!$a["a"] && $q->req == 1) {
        logger($clientip, "answer_404", "There wasn't an answer when there needs to be one. ({$i})");
    } else if (!$a["a"]) {
        $x->key[$i]->a = "Empty";
        continue;
    }

    if ($q->type == "text") {
        len_or_error($a["a"], $q->maxlen, "Answer {$i} was too long");
        $x->key[$i]->a = $a["a"];
    } else if ($q->type == "single") {
        if (!in_array($a["a"], $q->choices))
            logger($clientip, "choice_404", "That choice doesn't exist");
        $x->key[$i]->a = $a["a"];
    } else if ($q->type == "multiple") {
        foreach ($a["a"] as $z) {
            if (!in_array($z, $q->choices))
                logger($clientip, "choice_404", "That choice doesn't exist");
        }
        $x->key[$i]->a = implode("; ", $a["a"]);
    }
}

$all = json_decode($row["form_a"]);
$all[sizeof($all)] = $x;
$all = $sql->real_escape_string(json_encode($all));

$q = "UPDATE {$SQLTB} SET form_a='{$all}' WHERE form_id='" . $sql->real_escape_string($_POST["form_id"]) . "'";
$r = $sql->query($q);
if (!$r)
    logger($clientip, "sql_error", "SQL Error " . $sql->error);

logger($clientip, "success_vote", "A successful vote for ID" . $sql->real_escape_string($_POST["form_id"]));

echo '<p id="success">Submitted!</p><br /><a href="javascript:window.history.back()">Back</a>';

$sql->close();

if ($row["form_type"] == "poll") {
    echo '<br /><a href="javascript:document.getElementById(\'answers_form\').submit()">View answers</a>';
?>
<form id="answers_form" method="post" action="/viewForm/answers.php">
<input type="hidden" value="<?php echo htmlspecialchars($row["form_id"]); ?>" name="form_id" />
</form>
<?php
}
?>
