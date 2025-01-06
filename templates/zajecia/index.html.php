<?php

/** @var \App\Model\Zajecia[] $zajecia */
/** @var \App\Service\Router $router */

$title = 'Zajecia';
$bodyClass = 'index';

ob_start(); ?>
    <h1>Zajecia List</h1>

    <ul class="index-list">
        <?php foreach ($zajecia as $zaj): ?>
            <li><h3><?= $zaj->getId(), " ", $zaj->getGrupaId() ?></h3>
            </li>
        <?php endforeach; ?>
    </ul>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
