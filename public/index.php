<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoload.php';

$config = new \App\Service\Config();

$templating = new \App\Service\Templating();
$router = new \App\Service\Router();

$action = $_REQUEST['action'] ?? null;
switch ($action) {
    case 'info':
        $controller = new \App\Controller\InfoController();
        $view = $controller->infoAction();
        break;
    case 'main-index':
    case null:
        $controller = new \App\Controller\FilterController();
        $view = $controller->indexAction($templating, $router);
        break;
    case 'kalendarz-index':
        $controller = new \App\Controller\FilterController();
        $view = $controller->kalendarzAction($templating, $router);
        break;
    case 'kalendarz-events':
        $controller = new \App\Controller\FilterController();
        echo json_encode($controller->getEvents($_GET['start'], $_GET['end']));
        break;
    case 'kalendarz-filter':
        $controller = new \App\Controller\FilterController();
        echo json_encode($controller->filterEvents(json_decode(file_get_contents('php://input'), true)));
        break;
    default:
        $view = 'Not found';
        break;
}

if ($view) {
    echo $view;
}