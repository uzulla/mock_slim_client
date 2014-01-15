<html>
<head>
    <title>sample app</title>
</head>
<body>
    <?php if (isset($nickname)) { ?>
        hello: <span class="preview-name"><?= htmlspecialchars($nickname, ENT_QUOTES) ?></span>
    <?php } ?>
    <form action="/form" method="post">
        yourname: <input name="nickname">
        <input type="submit">
    </form>
</body>
</html>
