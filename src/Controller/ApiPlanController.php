<?php
namespace App\Controller;

use App\Model\Filter;
use App\Model\Lesson;
use App\Model\Subject;
use App\Model\Teacher;
use App\Model\Department;
use App\Model\StudyCourse;
use App\Model\RoomBuilding;
use App\Model\StudyGroup;

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


    public function getByType() {
        $kind = $_GET['kind'] ?? null;
        $query = $_GET['query'] ?? '';

        if ($kind) {
            switch ($kind) {
                case 'subject':
                    $items = empty($query) ? Subject::findAll() : Subject::findSubjectByName($query);
                    break;
                case 'teacher':
                    $items = empty($query) ? Teacher::findAll() : Teacher::findTeacherByName($query);
                    break;
                case 'classroom':
                    $items = empty($query) ? RoomBuilding::findAll() : RoomBuilding::findRoomBuildingByName($query);
                    break;
                case 'studygroup':
                    $items = empty($query) ? StudyGroup::findAll() : StudyGroup::findStudyGroupByName($query);
                    break;
                case 'department':
                    $items = empty($query) ? Department::findAll() : Department::findDepartmentByName($query);
                    break;
                case 'studycourse':
                    $items = empty($query) ? StudyCourse::findAll() : StudyCourse::findStudyCourseByName($query);
                    break;
                default:
                    $items = [];
            }

            $responseArray = array_map(fn($item) => $item->toArray(), $items);

            header('Content-Type: application/json');
            return json_encode($responseArray);
        }
        return json_encode([]);
    }
}