<?php
/** @var $router \App\Service\Router */

?>
<ul>
    <li><a href="<?= $router->generatePath('') ?>">Home</a></li>
    <li><a href="<?= $router->generatePath('post-index') ?>">Posts</a></li>
    <li><a href="<?= $router->generatePath('main-index') ?>">Zajecia</a></li>
    <li><a href="<?= $router->generatePath('kalendarz-index') ?>">Kalendarz</a></li>
</ul>
<?php
