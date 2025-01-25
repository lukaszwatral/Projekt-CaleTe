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
        $specialisation = $_GET['specialisation'] ?? null;

        $filteredLessons = Filter::filteredFind($teacher, $subject, $classroom, $studyGroup, $department, $subjectForm, $studyCourse, $semester, $yearOfStudy, $student, $major, $specialisation);


        $html = $templating->render('main/index.html.php', [
            'filteredLessons' => $filteredLessons,
            'router' => $router,
        ]);
        return $html;
    }

    public function eventAction(): void {
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
        $specialisation = $_GET['specialisation'] ?? null;

        $filteredLessons = Filter::filteredFind($teacher, $subject, $classroom, $studyGroup, $department, $subjectForm, $studyCourse, $semester, $yearOfStudy, $student, $major, $specialisation);

        $events = [];
        foreach ($filteredLessons as $lesson) {
            $events[] = [
                'title' => $lesson->getSubjectName(),
                'start' => $lesson->getDateStart(),
                'end' => $lesson->getDateEnd(),
                'description' => "Teacher: {$lesson->getTeacherName()}",
                'color' => '#007bff',
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($events);
    }


}