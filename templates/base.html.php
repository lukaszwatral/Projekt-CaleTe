<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/assets/dist/style.min.css">
    <title><?= $title ?? 'Custom Framework' ?></title>
</head>
<body <?= isset($bodyClass) ? "class='$bodyClass'" : '' ?>>
<header><?php require(__DIR__ . DIRECTORY_SEPARATOR . 'header.html.php') ?></header>
<!--<nav>--><?php //require(__DIR__ . DIRECTORY_SEPARATOR . 'nav.html.php') ?><!--</nav>-->
<main><?= $main ?? null ?></main>
<footer>&copy;<?= date('Y') ?> Lepszy Plan ZUT by Projekt-CałeTe</footer>
</body>
</html>
