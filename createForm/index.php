<?php include ('../vars.php'); ?>
<html>
<head>
<title>okforms - creating form</title>
<script>
var MAX_Q_LEN = <?php echo $MAX_QUESTION_LEN; ?>;
var MAX_C_LEN = <?php echo $MAX_CHOICE_LEN; ?>;
var MAX_T_LEN = <?php echo $MAX_TANSWER_LEN; ?>;
</script>
<script src="/js/createForm.min.js"></script>
</head>
<body>
<?php
if (getip() !== false)
    $clientip = getip();
else
    throwerror("Couldn't get your IP.");

if (!can_do($clientip, "create"))
    throwerror("You can't create a form.");
?>
<div style="left: 10%; right: 10%; width: 80%; position: absolute;">
    <form method="post" action="/createForm/create.php" id="newform">
        <input type="hidden" name="form-type" value="form" />
        <table style="display: table">
        <tbody>
            <tr data-type="title">
                <td>title</td>
                <td><input type="text" name="title" maxlength="<?php echo $MAX_TITLE_LEN; ?>" /></td>
            </tr>
            <tr data-type="description">
                <td>description</td>
                <td><textarea name="description" form="newform" maxlength="<?php echo $MAX_DESC_LEN; ?>"></textarea></td>
            </tr>
            <tr data-type="password">
                <td>password</td>
                <td><input type="text" name="password" maxlength="<?php echo $MAX_PASS_LEN; ?>" /></td>
            </tr>
            <tr data-type="expires">
                <td title="People won't be able to vote, but the data will stay">expires</td>
                <td><input type="date" name="expires" /></td>
            </tr>
            <tr>
                <td title="If allowed, people from the same IP can answer more than one time">allow same ip?</td>
                <td><input type="radio" value="true" name="sameip" />yes<br /><input type="radio" value="false" name="sameip" />no</td>
            </tr>
        </tbody>
        </table>
        <div id="formelem">
            <ol id="formq">
                <script>add_question()</script>
            </ol>
            <p> <button type="button" onclick="add_question()">Add a question</button></p>
        </div>
        <input type="submit" value="publish form" />
    </form>
</div>
</body>
</html>
