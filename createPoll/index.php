<?php include ('../vars.php'); ?>
<html>
<head>
    <title>okforms - creating poll</title>
    <script>
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
    <form method="post" action="/createForm/create.php" id="newpoll">
        <input type="hidden" name="form-type" value="poll" />
        <table style="display: table">
        <tbody>
            <tr data-type="title">
                <td>question</td>
                <td><input type="text" name="title" maxlength="<?php echo $MAX_TITLE_LEN; ?>" /></td>
            </tr>
            <tr data-type="expires">
                <td title="People won't be able to vote, but the data will stay">expires (minutes)</td>
                <td><input type="text" name="expires" /></td>
            </tr>
            <tr>
                <td title="If allowed, people from the same IP can answer more than one time">allow same ip?</td>
                <td><input type="radio" value="true" name="sameip" />yes<br /><input type="radio" value="false" name="sameip" />no</td>
            </tr>
        </tbody>
        </table>
        <div id="formelem">
                <p>Type:
                    <table>
                        <tr>
                            <td>Single choice</td>
                            <td><input type="radio" name="question[0][type]" value="single" onclick="gen_choices(0, 's')" /></td>
                        </tr>
                        <tr>
                            <td>Multiple choices</td>
                            <td><input type="radio" name="question[0][type]" value="multiple" onclick="gen_choices(0, 'm')" /></td>
                        </tr>
                        <tr>
                            <td> Text field</td>
                            <td><input type="radio" name="question[0][type]" value="text" onclick="gen_choices(0, 't')" /></td>
                        </tr>
                    </table>
                </p>
                <div id="q0-c"></div>
        </div>
        <br />
        <input type="submit" value="publish poll" />
    </form>
</div>
</body>
</html>