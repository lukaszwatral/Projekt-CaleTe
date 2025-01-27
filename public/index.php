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
    case 'event':
        $controller = new \App\Controller\FilterController();
        $view = $controller->eventAction();
        break;
        case 'apiplan':
        $controller = new \App\Controller\ApiPlanController();
        $view = $controller->getLessons();
        break;
        case 'apiplansubject':
        $controller = new \App\Controller\ApiPlanController();
        $view = $controller->getSubjects();
        break;

    default:
        $view = 'Not found';
        break;
}

if ($view) {
    echo $view;
}