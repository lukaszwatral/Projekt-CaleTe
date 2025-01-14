<?php
namespace App\Controller;

use App\Model\Filter;
use App\Service\Router;
use App\Service\Templating;

class FilterController
{
    public function indexAction(Templating $templating, Router $router): ?string
    {
        $teacher = $_GET['teacher'] ?? null;
        $subject = $_GET['subject'] ?? null;
        $classroom = $_GET['classroom'] ?? null;
        $studyGroup = $_GET['studyGroup'] ?? null;
        $department = $_GET['department'] ?? null;
        $subjectForm = $_GET['subjectForm'] ?? null;
        $studyCourse = $_GET['studyCourse'] ?? null;
        $semester = $_GET['semester'] ?? null;
        $yearOfStudy = $_GET['yearOfStudy'] ?? null;
        $student = $_GET['student'] ?? null;
        $major = $_GET['major'] ?? null;
        $specialization = $_GET['specialization'] ?? null;

        $filteredLessons = Filter::filteredFind($teacher, $subject, $classroom, $studyGroup, $department, $subjectForm, $studyCourse, $semester, $yearOfStudy, $student, $major, $specialization);

        $html = $templating->render('main/index.html.php', [
            'filteredLessons' => $filteredLessons,
            'router' => $router,
        ]);
        return $html;
    }

    public function kalendarzAction(Templating $templating, Router $router): ?string
    {
        $html = $templating->render('main/kalendarz.html.php', [
            'router' => $router,
        ]);
        return $html;
    }
    public function getEvents(string $start, string $end): array
    {
        $zajecia = Filter::filteredFind(
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
        $zajecia = Filter::filteredFind(
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