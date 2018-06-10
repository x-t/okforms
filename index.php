<html>
<head>
    <title>okforms</title>
    <?php include('./vars.php'); ?>
    <script src="/js/index.min.js"></script>
</head>
<body>
    <h4>okforms</h4>
    <p><em>google forms really went downhill</em></p>
    <p><a href="/createForm">create a form</a></p>
    <p><a href="/createPoll">create a poll</a></p>
    <p><small><a href="javascript:show_diff()">what's the difference?</a></small></p>
    <p id="diff"></p>
    <form method="get" action="viewForm/index.php">
        <input type="text" name="id" placeholder="Form/poll ID" />
        <input type="submit" value="view form" />
    </form>

    <h3>gdpr notice</h3>
    <p>things the system logs</p>
    <ul>
        <li title="they are considered sensitive data by the GDPR">ip addresses</li>
    </ul>
    <p>contact the system administrator for any action regarding your data: <?php echo $ADMIN_EMAIL; ?></p>
    <hr />
    <p>okforms is free software, released under the MIT (Expat) license, you can find the source code <a href="https://github.com/x-t/okforms">here</a></p>
</body>
</html>
