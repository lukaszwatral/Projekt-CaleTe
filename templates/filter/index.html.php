<?php

/** @var \App\Model\Filter $filter */
/** @var \App\Service\Router $router */
/** @var \App\Model\Filter[] $wydzials */

$bodyClass = "index";

ob_start(); ?>
    <h1>Filter</h1>
    <form action="<?= $router->generatePath('filter-index') ?>" method="get" class="filter-form">
        <?php require __DIR__ . DIRECTORY_SEPARATOR . '_form.html.php'; ?>
        <input type="hidden" name="action" value="filter-index">
    </form>

    <ul class="index-list">
        <?php foreach ($wydzials as $wydzial): ?>
            <li><h3><?= $wydzial->getGrupa() ?></h3>
            </li>
        <?php endforeach; ?>
    </ul>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';