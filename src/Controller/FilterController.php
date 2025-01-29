<?php
namespace App\Controller;

use App\Model\Lesson;
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
        $startOfWeek = $_GET['startOfWeek'] ?? null;
        $endOfWeek = $_GET['endOfWeek'] ?? null;

        $filteredLessons = Lesson::filteredFind($teacher, $subject, $classroom, $studyGroup, $department, $subjectForm, $studyCourse, $semester, $yearOfStudy, $student, $major, $specialisation);

        $startOfWeek = $_GET['startOfWeek'] ?? null;
        $endOfWeek = $_GET['endOfWeek'] ?? null;

        // Call the countSearchedValues function with start and end dates
        $counts = $this->countSearchedValues($startOfWeek, $endOfWeek);

        $html = $templating->render('main/index.html.php', [
            'counts' => $counts,
            'router' => $router,
        ]);
        return $html;
    }

    public function countSearchedValues(?string $startOfWeek, ?string $endOfWeek): array
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

        $filteredLessons = Lesson::filteredFind($teacher, $subject, $classroom, $studyGroup, $department, $subjectForm, $studyCourse, $semester, $yearOfStudy, $student, $major, $specialisation);

        $totalLessons = count($filteredLessons);
        $currentWeekLessons = 0;

        if ($startOfWeek && $endOfWeek) {
            $startOfWeek = strtotime($startOfWeek);
            $endOfWeek = strtotime($endOfWeek . ' 23:59:59');
        } else {
            $startOfWeek = strtotime('monday this week');
            $endOfWeek = strtotime('sunday this week 23:59:59');
        }

        foreach ($filteredLessons as $lesson) {
            $lessonStart = strtotime($lesson->getDateStart());
            if ($lessonStart >= $startOfWeek && $lessonStart <= $endOfWeek) {
                $currentWeekLessons++;
            }
        }

        return [
            'totalLessons' => $totalLessons,
            'currentWeekLessons' => $currentWeekLessons,
        ];
    }
    public function displayLessonCounts()
    {
        $startOfWeek = $_GET['startOfWeek'] ?? null;
        $endOfWeek = $_GET['endOfWeek'] ?? null;

        $counts = $this->countSearchedValues($startOfWeek, $endOfWeek);
    }

}