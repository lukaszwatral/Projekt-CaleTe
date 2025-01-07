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
        $semestr_studiow = $_GET['semestr_studiow'] ?? null;
        $rok_studiow = $_GET['rok_studiow'] ?? null;

        $zajecia = Zajecia::filteredFind($wykladowca, $przedmiot, $sala, $grupa, $wydzial, $forma_przedmiotu, $semestr_studiow, $rok_studiow);

        $html = $templating->render('zajecia/index.html.php', [
            'zajecia' => $zajecia,
            'router' => $router,
        ]);
        return $html;
    }
}