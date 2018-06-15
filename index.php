<html>
<head>
    <title>okforms</title>
    <?php include('./vars.php'); ?>
    <script src="/js/index.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/index.min.css" />
    <link id="fontawesome" rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous" />
</head>
<body>
    <div id="diffOverlay" class="overlay">
        <div class="overlayContent">
            <span class="overlayClose">&times;</span>
            <p>Polls can only have 1 question and the answers can be accessed publicly, while forms can have multiple questions and a password is needed to access the answers.</p>
        </div>
    </div>
    <div id="privacyOverlay" class="overlay">
        <div class="overlayContent">
            <span class="privacyClose">&times;</span>
            <p>Things this system collects</p>
            <ul>
                <li>IP addresses</li>
            </ul>
            <p>Contact the administrator with any action regarding your data: <?php echo $ADMIN_EMAIL; ?></p>
        </div>
    </div>
    <div class="cdiv">
    <h2>okforms</h2>
    <div class="createButtons">
    <a href="/createForm" class="button create">create a form</a>
    <a href="#" style="underline:none;text-decoration:none" id="overlayShow" title="What's the difference?"><span class="fas fa-question-circle"></span></a>
    <a href="/createPoll" class="button create">create a poll</a>
    </div>
    <div class="viewButton">
    <form id="viewForm" method="get" action="viewForm/index.php" autocomplete="off">
        <input class="viewID" type="text" name="id" placeholder="Form/poll ID" />
        <a href="javascript:document.getElementById('viewForm').submit()" class="button view">View form</a>
    </form>
    </div>
    </div>
    <div class="footer">
    <a href="#" id="gdprShow" title="Privacy"><span class="fas fa-lock"></span></a> | <a href="https://github.com/x-t/okforms" title="Source code"><span class="fab fa-github"></span></a>
    </div>
</body>
</html>
