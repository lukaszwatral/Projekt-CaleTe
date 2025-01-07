<?php

/** @var \App\Model\Zajecia[] $zajecia */
/** @var \App\Service\Router $router */

$title = 'Zajecia';
$bodyClass = 'index';

ob_start(); ?>
    <h1>Zajecia List</h1>

    <form action="<?= $router->generatePath('zajecia-index') ?>" method="get">
        <label for="wykladowca">Wykladowca:</label>
        <input type="text" id="wykladowca" name="wykladowca" value="<?= htmlspecialchars($_GET['wykladowca'] ?? '') ?>">

        <label for="przedmiot">Przedmiot:</label>
        <input type="text" id="przedmiot" name="przedmiot" value="<?= htmlspecialchars($_GET['przedmiot'] ?? '') ?>">

        <label for="sala">Sala:</label>
        <input type="text" id="sala" name="sala" value="<?= htmlspecialchars($_GET['sala'] ?? '') ?>">

        <label for="grupa">Grupa:</label>
        <input type="text" id="grupa" name="grupa" value="<?= htmlspecialchars($_GET['grupa'] ?? '') ?>">

        <label for="wydzial">Wydzial:</label>
        <input type="text" id="wydzial" name="wydzial" value="<?= htmlspecialchars($_GET['wydzial'] ?? '') ?>">

        <label for="forma_przedmiotu">Forma Przedmiotu:</label>
        <input type="text" id="forma_przedmiotu" name="forma_przedmiotu" value="<?= htmlspecialchars($_GET['forma_przedmiotu'] ?? '') ?>">

        <label for="semestr_studiow">Semestr Studiow:</label>
        <input type="text" id="semestr_studiow" name="semestr_studiow" value="<?= htmlspecialchars($_GET['semestr_studiow'] ?? '') ?>">

        <label for="rok_studiow">Rok Studiow:</label>
        <input type="text" id="rok_studiow" name="rok_studiow" value="<?= htmlspecialchars($_GET['rok_studiow'] ?? '') ?>">

        <button type="submit">Filter</button>
    </form>

    <ul class="index-list">
        <?php if (empty($zajecia)): ?>
            <li>No results found.</li>
        <?php else: ?>
            <?php foreach ($zajecia as $zaj): ?>
                <li><h3><?= $zaj->getId(), ". " , $zaj->getDataStart(), "-", $zaj->getDataKoniec(), ", wyk: ", $zaj->getWykladowcaId(), ", p: ", $zaj->getPrzedmiotId(), ", s: ", $zaj->getSalaId(), ", gr: ", $zaj->getGrupaId(), ", wydz: ", $zaj->getWydzialId(), ", typ: ", $zaj->getTokStudiowId(), ", sem: ", $zaj->getSemestr() ?></h3>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';