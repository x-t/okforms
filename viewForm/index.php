<?php include '../vars.php'; ?>
<html>
<head>
<title>viewing form</title>
<link id="fontawesome" rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous" />
<link rel="stylesheet" type="text/css" href="/css/viewForm.min.css" />
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
    $x = new stdClass();
    $x->type = "form_expire";
    if ($row["form_type"] == "form")
        $x->desc = "Viewing an expired form";
    else
        $x->desc = "Viewing an expired poll";
    genErrPage($x);
}
?>
<ul>
    <li><a href="/">okforms</a></li>
    <li class="r"><a href="/viewForm/answers.php?id=<?php echo $row["form_id"]; ?>"><span class="fas fa-chart-pie"></span> View answers</a></li>
    <li class="r"><a href="/viewForm/report.php?id=<?php echo $row["form_id"]; ?>"><span class="fas fa-flag"></span> Report form</a></li>
</ul>

<div class="mainForm">
<?php
echo "<h2 class=\"formTitle c\">" . htmlspecialchars($row['form_title']) . "</h3>";
if ($row["form_desc"])
    echo "<p class=\"formDesc c\">" . htmlspecialchars($row['form_desc']) . "</p>";
$qs = json_decode($row["form_q"]);

echo '<form action="/viewForm/vote.php" method="post" id="vote">';
printf('<input type="hidden" name="form_id" value="%s" />', htmlspecialchars($_GET['id']));
$i = 0;
foreach ($qs as $s) {
    if ($row["form_type"] == "form") {
        $required = "";
        if ($s->req === 1)
            $required = "<span class='mustAnswer'>*</span> ";
        printf("<h4>%s%d. %s</h4>", $required, $i + 1, htmlspecialchars($s->q));
    }
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
    } else if ($s->type == "dropdown") {
        printf('<p><div class="droplist"><select name="k[%d][a]">', $i);
        foreach ($s->choices as $c) {
            printf('<option value="%s">%s</option>', htmlspecialchars($c), htmlspecialchars($c));
        }
        echo '</select><span class="fas fa-angle-down"></span></div></p>';
    }

    $i++;
}

$sql->close();

$c = can_do($clientip, "vote");
if ($c->can === false) {
    $x = new stdClass();
    if ($c->why == "ban") {
        $x->type = "ban";
        $x->desc = "You are banned. Reason: {$c->reason}.";
        printf("<p class=\"c\">%s</p>", $x->desc);
    } else if ($c->why == "timeout") {
        $x->type = "timeout";
        $x->desc = "You have to wait {$c->timeout} seconds before voting";
        printf("<p class=\"c\">%s</p>", $x->desc);
    }
} else {
    echo '<button type="submit" class="submitBtn">Submit answers</button>';
}

?>
</form>
</div>
</body>
</html>
