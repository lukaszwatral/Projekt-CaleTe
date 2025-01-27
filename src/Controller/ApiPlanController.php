<?php
namespace App\Controller;

use App\Model\Filter;
use App\Model\Lesson;
use App\Model\Subject;

class ApiPlanController
{
    public function getLessons()
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
        $start = $_GET['start'] ?? null;
        $end = $_GET['end'] ?? null;

        if (is_null($teacher) && is_null($subject) && is_null($classroom) && is_null($studyGroup) && is_null($department) && is_null($subjectForm) && is_null($studyCourse) && is_null($semester) && is_null($yearOfStudy) && is_null($student) && is_null($major) && is_null($specialisation) && is_null($start) && is_null($end)) {
            return json_encode([]);
        }
        $filteredLessons = Lesson::filteredFind($teacher, $subject, $classroom, $studyGroup, $department, $subjectForm, $studyCourse, $semester, $yearOfStudy, $student, $major, $specialisation, $start, $end);

        $filteredLessonsArray = [];
        foreach ($filteredLessons as $lesson) {
            $filteredLessonsArray[] = $lesson->toArray();
        }

        header('Content-Type: application/json');
        return json_encode($filteredLessonsArray);

    }

    public function getSubjects() {
        $name = $_GET['name'] ?? null;

        $filteredSubjects = Subject::findSubjectByName($name);

        $filteredSubjectsArray = [];
        foreach ($filteredSubjects as $subject) {
            $filteredSubjectsArray[] = $subject->toArray();
        }

        header('Content-Type: application/json');
        return json_encode($filteredSubjectsArray); }
}