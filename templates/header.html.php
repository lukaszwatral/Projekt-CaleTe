<?php
/** @var $router \App\Service\Router */

?>

    <a href="<?= $router->generatePath('') ?>">
        <img src="/assets/images/logo-zut.svg" alt="Logo">
    </a>
    <div id="customization-buttons">
        <button type="button" id="change-font-btn" class="btn">Zmień czcionkę</button>
        <button type="button" id="dark-mode-btn" class="btn">Ciemny motyw</button>
    </div>
<?php
