<html>
<head>
<link id="fontawesome" rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous" />
</head>
<body>
<?php

include '../vars.php';

if (getip() !== false)
    $clientip = getip();
else
    throwerror("Couldn't get your IP.");

$c = can_do($clientip, "create");
if ($c->can === false) {
    $x = new stdClass();
    if ($c->why == "ban") {
        $x->type = "ban";
        $x->desc = "You are banned. Reason: {$c->reason}.";
        genErrPage($x);
    } else if ($c->why == "timeout") {
        $x->type = "timeout";
        $x->desc = "You have to wait {$c->timeout} seconds before making a new form.";
        genErrPage($x);
    }
}

$sql = sqlcon();

if (!isset($_POST["form-type"]) || !$_POST["form-type"])
    logger($clientip, "form_param_missing", "No form type was specified");
if (!isset($_POST["question"]) || !$_POST["question"])
    logger($clientip, "form_param_missing", "No question data");
if (!isset($_POST["title"]) || !$_POST["title"])
    logger($clientip, "form_param_missing", "No title specified");
if (!isset($_POST["expires"]) || !$_POST["expires"])
    logger($clientip, "form_param_missing", "Expire date not set");
if (!isset($_POST["sameip"]) || !$_POST["sameip"])
    logger($clientip, "form_param_missing", "Same IP rule not set");

if ($_POST["form-type"] === "form") {
    if (!isset($_POST["password"]) || !$_POST["password"])
        logger($clientip, "form_param_missing", "No form password was specified");
}

$x = new stdClass();

$x->ip = $clientip;

if ($_POST["sameip"] === "false")
    $x->sameip = 0;
else if ($_POST["sameip"] === "true")
    $x->sameip = 1;
else
    logger($clientip, "form_param_sense", "Same IP rule makes no sense");

if ($_POST["form-type"] == "form")
    $x->form_type = "form";
else if ($_POST["form-type"] == "poll")
    $x->form_type = "poll";
else
    logger($clientip, "form_type_unknown", "Form type is unknown");

$x->made = time();
if ($x->form_type == "form") {
    $expires = strtotime($_POST["expires"]);
    if ($expires === false) 
        logger($clientip, "expire_bogus", "Expire date seems to be bogus.");
    else if ($x->made >= $expires)
        logger($clientip, "expire_magic", "It expired before it was made. What magic is that?");
    else
        $x->expires = $expires;
} else {
    $expires = intval($_POST["expires"]);
    if ($expires > 525600 || $expires <= 0)
        logger($clientip, "expire_nonsense", "The expire time isn't realistic.");
    $expiredate = $x->made + $expires * 60;
    $x->expires = $expiredate;
}

len_or_error($_POST["title"], $MAX_TITLE_LEN, "Title &gt; {$MAX_TITLE_LEN} chars");
$x->title = $sql->real_escape_string($_POST["title"]);

if ($x->form_type == "form") {
    len_or_error($_POST["description"], $MAX_DESC_LEN, "Description &gt; {$MAX_DESC_LEN} chars");
    len_or_error($_POST["password"], $MAX_PASS_LEN, "Password &gt; {$MAX_PASS_LEN} chars");

    if ($_POST["description"])
        $x->description = $sql->real_escape_string($_POST["description"]);
    $x->password = $sql->real_escape_string(hash('sha256', $_POST["password"]));
}

$i = 0;
foreach ($_POST["question"] as $question) {
    $realnum = $i + 1;
    if (!isset($question["type"]) || !$question["type"])
        logger($clientip, "form_param_missing", "Question type not specified ({$realnum})");

    if (!isset($question["req"]) || !$question["req"]) {
        if ($x->form_type == "form")
            logger($clientip, "form_param_missing", "Requirement not specified ({$realnum})");
        else 
            $question["req"] = "yes";
    }

if (!isset($question["q"]) || !$question["q"]) {
    if ($x->form_type == "form")
        logger($clientip, "form_param_missing", "No given question ({$realnum})");
    else
        $question["q"] = $x->title;
}

    len_or_error($question["q"], $MAX_QUESTION_LEN, "Question length &gt; {$MAX_QUESTION_LEN}");

    if ($question["req"] == "yes")
        $x->question[$i]->req = 1;
    else if ($question["req"] == "no") 
        $x->question[$i]->req = 0;
    else
        logger($clientip, "requirement_unknown", "Unknown requirement ({$realnum})");

    $x->question[$i]->q = $question["q"];

    if ($question["type"] == "single") {
        if (!isset($question["choices"]) || !$question["choices"])
            logger($clientip, "choice_err", "No specified choices ({$realnum})");
        $ii = 0;
        foreach ($question["choices"] as $choice) {
            if (!isset($choice) || !$choice)
                logger($clientip, "choice_err", "Choice not set ({$realnum})");
            len_or_error($choice, $MAX_CHOICE_LEN, "Choice length &gt; {$MAX_CHOICE_LEN}");
            $x->question[$i]->choices[$ii] = $choice;
            $ii++;
        }
        $x->question[$i]->type = "single";
    } else if ($question["type"] == "dropdown") {
            if (!isset($question["choices"]) || !$question["choices"])
                logger($clientip, "choice_err", "No specified choices ({$realnum})");
            $ii = 0;
            foreach ($question["choices"] as $choice) {
                if (!isset($choice) || !$choice)
                    logger($clientip, "choice_err", "Choice not set ({$realnum})");
                len_or_error($choice, $MAX_CHOICE_LEN, "Choice length &gt; {$MAX_CHOICE_LEN}");
                $x->question[$i]->choices[$ii] = $choice;
                $ii++;
            }
            $x->question[$i]->type = "dropdown";
    } else if ($question["type"] == "multiple") {
        if (!isset($question["choices"]) || !$question["choices"])
            logger($clientip, "choice_err", "No specified choices ({$realnum})");
        $ii = 0;
        foreach ($question["choices"] as $choice) {
            if (!isset($choice) || !$choice)
                logger($clientip, "choice_err", "Choice not set ({$realnum})");
            len_or_error($choice, $MAX_CHOICE_LEN, "Choice length &gt; {$MAX_CHOICE_LEN}");
            $x->question[$i]->choices[$ii] = $choice;
            $ii++;
        }
        $x->question[$i]->type = "multiple";
    } else if ($question["type"] == "text") {
        if (!isset($question["maxlen"]) || !$question["maxlen"] || !intval($question["maxlen"]))
            logger($clientip, "max_len_miss", "Max length not specified ({$realnum})");
        len_or_error($question["maxlen"], $MAX_TANSWER_LEN, "Max length &gt; " . str_repeat("9", $MAX_TANSWER_LEN));
        $x->question[$i]->type = "text";
        $x->question[$i]->maxlen = intval($question["maxlen"]);
    } else {
        logger($clientip, "question_type_err", "Unknown question type ({$realnum})");
    }

    $i++;
}

$q = "SELECT form_id FROM {$SQLTB};";
$r = $sql->query($q);

if ($r->num_rows > 0) {
    while(1) {
        $x->id = ($x->form_type == "form" ? 'f' . gen64(10) : 'p' . gen64(10));
        $q = "SELECT form_id FROM {$SQLTB} WHERE form_id=\"{$x->id}\"";
        $r1 = $sql->query($q);
        if ($r1->num_rows == 0)
            break;
    }
} else {
    $x->id = ($x->form_type == "form" ? 'f' . gen64(10) : 'p' . gen64(10));
}


$q = "INSERT INTO {$SQLTB}(form_id, form_ip, form_type, form_made, form_expires, form_pass, form_title, form_q, form_sameip) VALUES('{$x->id}', '{$x->ip}', '{$x->form_type}', '{$x->made}', '{$x->expires}', '{$x->password}', '{$x->title}', '" . $sql->real_escape_string(json_encode($x->question)) . "', '{$x->sameip}')";

$r = $sql->query($q);
if (!$r)
    logger($clientip, "sql_err", "SQL error " . $sql->error);

if ($x->description) {
    $q = "UPDATE {$SQLTB} SET form_desc='{$x->description}' WHERE form_id='{$x->id}'";
    $r = $sql->query($q);
    if (!$r)
        logger($clientip, "sql_err", "SQL error " . $sql->error);
}

logger($clientip, "success_new", "Successfully created new post {$x->id}");
$sql->close();

header("Location: /viewForm/?id={$x->id}");

?>
</body>
</html>
