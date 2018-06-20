<?php include ('../vars.php'); ?>
<html>
<head>
<title>okforms - creating form</title>
<script>
var MAX_Q_LEN = <?php echo $MAX_QUESTION_LEN; ?>;
var MAX_C_LEN = <?php echo $MAX_CHOICE_LEN; ?>;
var MAX_T_LEN = <?php echo $MAX_TANSWER_LEN; ?>;
var POLL_MODE = false;
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
        $x->desc = "You have to wait {$c->timeout} seconds before making a new form.";
        genErrPage($x);
    }
}
?>
<ul>
    <li><strong><a class="ex">Create a question</a></strong></li>
    <li id="choiceSingle"><a href="javascript:add_question('s')"><span class="fas fa-check-circle"></span> Single choice</a></li>
    <li id="choiceMult"><a href="javascript:add_question('m')"><span class="fas fa-check-square"></span> Multiple choices</a></li>
    <li id="choiceText"><a href="javascript:add_question('t')"><span class="fas fa-font"></span> Text choice</a></li>
    <li id="choiceDrop"><a href="javascript:add_question('d')"><span class="fas fa-angle-down"></span> Dropdown</a></li>
    <li><a class="ex"></a></li>
    <li><a href="javascript:document.getElementById('newform').submit()"><span class="fas fa-arrow-circle-right"></span> Submit form</a></li>
</ul>
<div class="Main">
<div class="formMain">
<div class="fdiv">
    <form method="post" action="/createForm/create.php" id="newform" autocomplete="off">
        <input type="hidden" name="form-type" value="form" />
        <br />
        <div class="c"><input type="text" name="title" class="formTitle" maxlength="<?php echo $MAX_TITLE_LEN; ?>" placeholder="Form title" /></div>
        <div class="c"><textarea class="formDescription" name="description" form="newform" maxlength="<?php echo $MAX_DESC_LEN; ?>" placeholder="Form description (optional)"></textarea></div>
        <div class="c"><input class="formTitle" type="password" name="password" maxlength="<?php echo $MAX_PASS_LEN; ?>" placeholder="Form password" /></div>
        <div style="margin-left:33px;margin-bottom:6px;">
        <span style="font-size:16;" title="If allowed, people from the same IP can answer more than one time">Allow same IP?</span>
        <div class="droplist"><select name="sameip">
            <option value="false">No</option>
            <option value="true">Yes</option>
        </select><span class="fas fa-angle-down"></span></div>
        </div>
        <table style="display: table;margin-left: 30px;">
        <tbody>
            <tr data-type="expires">
                <td title="People won't be able to vote, but the data will stay">Form expires</td>
                <td><input class="formExpires" type="date" name="expires" /></td>
            </tr>
        </tbody>
        </table>
        <div id="formelem">
        </div>
</div>
</div>
</div>
</form>
</body>
</html>
