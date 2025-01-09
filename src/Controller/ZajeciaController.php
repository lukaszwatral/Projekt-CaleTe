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
        $student = $_GET['student'] ?? null;

        $zajecia = Zajecia::filteredFind($wykladowca, $przedmiot, $sala, $grupa, $wydzial, $forma_przedmiotu, $typ_studiow, $semestr_studiow, $rok_studiow, $student);

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
    public function getEvents(string $start, string $end): array
    {
        $zajecia = Zajecia::filteredFind(
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null
        );

        $events = [];
        foreach ($zajecia as $zaj) {
            $events[] = [
                'title' => $zaj->getPrzedmiotName(),
                'start' => $zaj->getDataStart(),
                'end' => $zaj->getDataKoniec(),
                'description' => "Prowadzący: {$zaj->getWykladowcaName()}",
                'color' => '#007bff',
            ];
        }

        return $events;
    }
    public function filterEvents(array $filters): array
    {
        $zajecia = Zajecia::filteredFind(
            $filters['wykladowca'] ?? null,
            $filters['przedmiot'] ?? null,
            $filters['sala'] ?? null,
            $filters['grupa'] ?? null,
            $filters['wydzial'] ?? null,
            $filters['forma_przedmiotu'] ?? null,
            $filters['typ_studiow'] ?? null,
            $filters['semestr_studiow'] ?? null,
            $filters['rok_studiow'] ?? null,
            $filters['student'] ?? null
        );

        $events = [];
        foreach ($zajecia as $zaj) {
            $events[] = [
                'title' => $zaj->getPrzedmiotName(),
                'start' => $zaj->getDataStart(),
                'end' => $zaj->getDataKoniec(),
                'description' => "Prowadzący: {$zaj->getWykladowcaName()}",
                'color' => '#007bff',
            ];
        }

        return $events;
    }
}