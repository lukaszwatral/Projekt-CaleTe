<?php
namespace App\Controller;

use App\Model\Zajecia;
use App\Service\Router;
use App\Service\Templating;

class ZajeciaController
{
    public function indexAction(Templating $templating, Router $router): ?string
    {
        $wykladowca = $_GET['wykladowca'] ?? null;
        $przedmiot = $_GET['przedmiot'] ?? null;
        $sala = $_GET['sala'] ?? null;
        $grupa = $_GET['grupa'] ?? null;
        $wydzial = $_GET['wydzial'] ?? null;
        $forma_przedmiotu = $_GET['forma_przedmiotu'] ?? null;
        $typ_studiow = $_GET['typ_studiow'] ?? null;
        $semestr_studiow = $_GET['semestr_studiow'] ?? null;
        $rok_studiow = $_GET['rok_studiow'] ?? null;

        $zajecia = Zajecia::filteredFind($wykladowca, $przedmiot, $sala, $grupa, $wydzial, $forma_przedmiotu, $typ_studiow, $semestr_studiow, $rok_studiow);

        $html = $templating->render('zajecia/index.html.php', [
            'zajecia' => $zajecia,
            'router' => $router,
        ]);
        return $html;
    }
    public function kalendarzAction(Templating $templating, Router $router): ?string
    {

        $html = $templating->render('zajecia/kalendarz.html.php', [
            'router' => $router,
        ]);
        return $html;
    }

}