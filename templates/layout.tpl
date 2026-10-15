<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{block name="title"}Блог{/block}</title>
</head>
<body>
    <header class="header">
        <div class="container">
            <a class="header__logo" href="/">Блог</a>
        </div>
    </header>

    <main class="container">
        {block name="content"}{/block}
    </main>

    <footer class="footer">
        <div class="container">Тестовый блог на PHP, Smarty и MySQL</div>
    </footer>
</body>
</html>
