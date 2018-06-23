<?php include '../vars.php'; ?>
<html>
<head>
    <title>okforms - creating poll</title>
    <script>
    var MAX_C_LEN = <?php echo $MAX_CHOICE_LEN; ?>;
    var MAX_T_LEN = <?php echo $MAX_TANSWER_LEN; ?>;
    var POLL_MODE = true;
    </script>
    <script src="/js/createForm.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/createForm.min.css" />
    <link id="fontawesome" rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous" />
</head>
<body>
<?php
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
        $x->desc = "You have to wait <span id=\"timeoutTime\">{$c->timeout}</span> seconds before making a new poll.";
        genErrPage($x);
    }
}
?>

<ul>
    <li><strong><a class="ex">Change type</a></strong></li>
    <li id="choiceSingle"><a href="javascript:gen_choices(0, 's')"><span class="fas fa-check-circle"></span> Single choice</a></li>
    <li id="choiceMult"><a href="javascript:gen_choices(0, 'm')"><span class="fas fa-check-square"></span> Multiple choices</a></li>
    <li id="choiceText"><a href="javascript:gen_choices(0, 't')"><span class="fas fa-font"></span> Text choice</a></li>
    <li id="choiceDrop"><a href="javascript:gen_choices(0, 'd')"><span class="fas fa-angle-down"></span> Dropdown</a></li>
    <li id="choiceLinear"><a href="javascript:gen_choices(0, 'l')"><span class="fas fa-ellipsis-h"></span> Linear scale</a></li>
    <li><a class="ex"></a></li>
    <li><a id="addChoiceLi" style="display:none" href="javascript:add_choice(0)"><span class="fas fa-plus"></span> Add choice</a></li>
    <li><a class="ex"></a></li>
    <li><a href="javascript:document.getElementById('newform').submit()"><span class="fas fa-arrow-circle-right"></span> Submit poll</a></li>
</ul>

<div class="formMain">
    <div class="fdiv">
    <form method="post" action="/createForm/create.php" id="newform" autocomplete="off">
        <input type="hidden" name="form-type" value="poll" />
        <input type="text" name="title" class="formTitle" maxlength="<?php echo $MAX_TITLE_LEN; ?>" placeholder="Question" />
        <input type="text" name="expires" class="formTitle" maxlength="10" placeholder="Expires in (minutes)" />
        <div style="margin-bottom:6px;">
        <span style="font-size:16;" title="If allowed, people from the same IP can answer more than one time">Allow same IP?</span>
        <div class="droplist"><select name="sameip">
            <option value="false">No</option>
            <option value="true">Yes</option>
        </select><span class="fas fa-angle-down"></span></div>
        </div>
        <div id="formelem">
            <br />
            <br />
            <div id="q0">
                <input type="hidden" name="question[0][type]" id="pollAnsType" />
                <div id="q0-c"></div>
            </div>
            
        </div>
    </div>
        <br />
    </form>
</div>
</body>
</html>