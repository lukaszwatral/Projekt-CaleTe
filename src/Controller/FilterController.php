<?php
namespace App\Controller;

use App\Exception\NotFoundException;
use App\Model\Filter;
use App\Service\Router;
use App\Service\Templating;

class FilterController {
    public function indexAction(Templating $templating, Router $router): ?string
    {
        $wydzials = Filter::findAll();
        $html = $templating->render('filter/index.html.php', [
            'wydzials' => $wydzials,
            'router' => $router,
        ]);
        return $html;
    }

//    public function index(Router $router)
//    {
//        $filter = new Filter();
//        if ($_GET) {
//            $filter->setId($_GET['id'] ?? null);
//            $filter->setWykladowca($_GET['wykladowca'] ?? null);
//            $filter->setSala($_GET['sala'] ?? null);
//            $filter->setPrzedmiot($_GET['przedmiot'] ?? null);
//            $filter->setGrupa($_GET['grupa'] ?? null);
//            $filter->setNumerAlbumu($_GET['numer_albumu'] ?? null);
//        }
//
//        $wydzials = Filter::findAll();
//
//        require __DIR__ . '/../../templates/filter/index.html.php';
//    }
}