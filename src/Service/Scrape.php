<?php
namespace App\Service;

use App\Model\Department;
use App\Model\RoomBuilding;
use App\Model\GroupStudent;
use App\Model\Student;
use App\Model\StudyCourse;
use App\Model\StudyGroup;
use App\Model\Subject;
use App\Model\Teacher;
use App\Model\Lesson;
use App\Model\CourseStudent;
use App\Service\Config;
//@todo Modele, zmiany nazwy tabel

class Scrape
{
    //@todo Pamiętaj o ustawieniu PDO jak klase tworzysz
    private \PDO $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * @throws \Exception
     */
    public function scrapeData(string $department){
        $apiURL = "https://plan.zut.edu.pl/schedule_student.php?kind=apiwi&department={$department}&start=2025-01-13&end=2025-01-19";
        $response = file_get_contents($apiURL);
        if(!$response) {
            die('No response');
        }

        $json = json_decode($response, true);


        foreach ($json as $item) {
            //Wydział
            if (isset($item['wydzial']) && isset($item['wydz_sk'])) {
                $departValue = $item['wydzial'];
                $departShortValue = $item['wydz_sk'];
                $this->insertDepartment($departValue, $departShortValue);
            }
            //Sala z budynkiem
            if(isset($item['room'])){
                $room = $item['room'];
                $depart = $item['wydzial'];
                $this->insertRoomBuilding($room, $depart);
            }
            //Tok studiów
            if(isset($item['tok_name'])){
                $tok_name = $item['tok_name'];
                if($tok_name == null){
                    $tok_name = "Brak";
                }
                $shortType = $item['typ_sk'];
                if($shortType == null){
                    $shortType = "Brak";
                }
                $shortKind = $item['rodzaj_sk'];
                if($shortKind == null){
                    $shortKind = "Brak";
                }
                $specialisation = $item['specjalnosc'];
                if($specialisation == null){
                    $specialisation = "Brak";
                }
                $major = $item['kierunek'];
                if($major == null){
                    $major = "Brak";
                }
                $this->insertStudyCourse($tok_name, $shortType, $shortKind, $specialisation, $major);
            }
            //Przedmiot
            if(isset($item['subject'])){
                $subject = $item['subject'];
                $form = $item['lesson_form'];
                //Dane do wyszukanie StudyCurse
                $tok_name = $item['tok_name'];
                if($tok_name == null){
                    $tok_name = "Brak";
                }
                $shortType = $item['typ_sk'];
                if($shortType == null){
                    $shortType = "Brak";
                }
                $shortKind = $item['rodzaj_sk'];
                if($shortKind == null){
                    $shortKind = "Brak";
                }
                $specialisation = $item['specjalnosc'];
                if($specialisation == null){
                    $specialisation = "Brak";
                }
                $major = $item['kierunek'];
                if($major == null){
                    $major = "Brak";
                }
                $this->insertSubject($subject, $form, $tok_name, $shortType, $shortKind, $specialisation, $major);
            }
            //Grupa
            if(isset($item['group_name'])){
                $groupName = $item['group_name'];
                $this->insertStudyGroup($groupName);
            }
            //Wykładowca
            if(isset($item['worker'])){
                $firstName = $item['imie'];
                $lastName = $item['nazwisko'];
                $title = $item['tytul'];
                if($title == null){
                    $title = "Brak";
                }
                $this->insertTeacher($firstName, $lastName, $title);
            }
            //Zajęcia
            if(isset($item['id'])){
                $id = $item['id'];
                $dateSstart = $item['start'];
                $dateEnd = $item['end'];
                $cover = $item['worker_cover'];
                if($cover == null){
                    $cover = "Brak";
                }
                $teacherFirstName = $item['imie'];
                $teacherLastName = $item['nazwisko'];
                $teacherTitle = $item['tytul'];
                if($teacherTitle == null){
                    $teacherTitle = "Brak";
                }
                $department = $item['wydzial'];
                $group = $item['group_name'];
                //Dane do toku studiów
                $tok_name = $item['tok_name'];
                if($tok_name == null){
                    $tok_name = "Brak";
                }
                $shortType = $item['typ_sk'];
                if($shortType == null){
                    $shortType = "Brak";
                }
                $shortKind = $item['rodzaj_sk'];
                if($shortKind == null){
                    $shortKind = "Brak";
                }
                $specialisation = $item['specjalnosc'];
                if($specialisation == null){
                    $specialisation = "Brak";
                }
                $major = $item['kierunek'];
                if($major == null){
                    $major = "Brak";
                }
                $subject = $item['subject'];
                $form = $item['lesson_form'];
                $room = $item['room'];
                $semester = $item['semestr'];
                $this->insertLesson($id, $dateSstart, $dateEnd, $cover, $teacherFirstName, $teacherLastName, $teacherTitle, $department, $group, $tok_name, $shortType, $shortKind, $specialisation, $major, $subject, $form, $room, $semester);
            }
        }
    }

    private function insertDepartment(string $department, string $departmentShort){
        $departmentModel = new Department();
        $result = $departmentModel->findDepartment($department);
        if($result == null) {
            $departmentModel->setName($department);
            $departmentModel->setShortName($departmentShort);
            $departmentModel->save();
        }
    }
    private function insertRoomBuilding(string $room, string $department){
        $departmentModel = new Department();
        $departmentResult = $departmentModel->findDepartment($department);
        if ($departmentResult) {
            $departmentId = $departmentResult->getId();
            $roomBuildingModel = new RoomBuilding();
            $roomResult = $roomBuildingModel->findRoom($room, $departmentId);
            if (!$roomResult) {
                $roomBuildingModel->setBuildingRoom($room);
                $roomBuildingModel->setDepartmentId($departmentId);
                $roomBuildingModel->save();
            }
        } else {
            throw new \Exception("Department not found");
        }
    }
    private function insertStudyCourse(string $tok_name, string $shortType, string $shortKind, string $specialisation, string $major){
        $studyCourseModel = new StudyCourse();
        $result = $studyCourseModel->findStudyCourse($tok_name, $shortType, $shortKind, $specialisation, $major);
        if(!$result){
            $studyCourseModel->setTokName($tok_name);
            $studyCourseModel->setShortType($shortType);
            $studyCourseModel->setShortKind($shortKind);
            $studyCourseModel->setSpecialisation($specialisation);
            $studyCourseModel->setMajor($major);
            $studyCourseModel->save();
        }
    }
    private function insertSubject(string $subject, string $form , string $tok_name, string $shortType, string $shortKind, string $specialisation, string $major){
        $studyCourseModel = new StudyCourse();
        $result = $studyCourseModel->findStudyCourse($tok_name, $shortType, $shortKind, $specialisation, $major);
        if($result){
            $studyCourseId = $result->getId();
            $subjectModel = new Subject();
            $result = $subjectModel->findSubject($subject, $form, $studyCourseId);
            if(!$result){
                $subjectModel->setName($subject);
                $subjectModel->setForm($form);
                $subjectModel->setStudyCourseId($studyCourseId);
                $subjectModel->save();
            }
        }
    }
    private function insertStudyGroup(string $groupName){
        $studyGroupModel = new StudyGroup();
        $result = $studyGroupModel->findStudyGroup($groupName);
        if(!$result){
            $studyGroupModel->setName($groupName);
            $studyGroupModel->save();
        }
    }
    private function insertTeacher(string $firstName, string $lastName, string $title){
        $teacherModel = new Teacher();
        $result = $teacherModel->findTeacher($firstName, $lastName, $title);
        if(!$result){
            $teacherModel->setFirstName($firstName);
            $teacherModel->setLastName($lastName);
            $teacherModel->setTitle($title);
            $teacherModel->save();
        }
    }
    private function insertLesson(int $id, string $dateSstart, string $dateEnd, string $cover, string $teacherFirstName,  string $teacherLastName, string $teacherTitle, string $department, string $group, string $tok_name, string $shortType, string $shortKind, string $specialisation, string $major, string $subject, string $form, string $room, int $semester){
        $teacherModel = new Teacher();
        $teacherResult = $teacherModel->findTeacher($teacherFirstName, $teacherLastName, $teacherTitle);
        if($teacherResult){
            $teacherId = $teacherResult->getId();
            $departmentModel = new Department();
            $departmentResult = $departmentModel->findDepartment($department);
            if($departmentResult){
                $departmentId = $departmentResult->getId();
                $groupModel = new StudyGroup();
                $groupResult = $groupModel->findStudyGroup($group);
                if($groupResult){
                    $groupId = $groupResult->getId();
                    $studyCourseModel = new StudyCourse();
                    $studyCourseResult = $studyCourseModel->findStudyCourse($tok_name, $shortType, $shortKind, $specialisation, $major);
                    if($studyCourseResult){
                        $studyCourseId = $studyCourseResult->getId();
                        $subjectModel = new Subject();
                        $subjectResult = $subjectModel->findSubject($subject, $form, $studyCourseId);
                        if($subjectResult){
                            $subjectId = $subjectResult->getId();
                            $roomBuildingModel = new RoomBuilding();
                            $roomResult = $roomBuildingModel->findRoom($room, $departmentId);
                            if($roomResult){
                                $roomId = $roomResult->getId();
                                $lessonModel = new Lesson();
                                $result = $lessonModel->findLesson($id);
                                if(!$result){
                                    $lessonModel->setId($id);
                                    $lessonModel->setDateStart($dateSstart);
                                    $lessonModel->setDateEnd($dateEnd);
                                    $lessonModel->setTeacherCover($cover);
                                    $lessonModel->setSemester($semester);
                                    $lessonModel->setTeacherId($teacherId);
                                    $lessonModel->setDepartmentId($departmentId);
                                    $lessonModel->setGroupId($groupId);
                                    $lessonModel->setStudyCourseId($studyCourseId);
                                    $lessonModel->setSubjectId($subjectId);
                                    $lessonModel->setRoomId($roomId);
                                    $lessonModel->save();
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    public function insertGroupStudent(int $studentId){
        $semester_start = Config::get('semesters')[Config::get('current_semester')]['start'];
        $semester_end = Config::get('semesters')[Config::get('current_semester')]['end'];

        $apiURL = "https://plan.zut.edu.pl/schedule_student.php?number={$studentId}&{$semester_start}&end={$semester_end}";
        $response = file_get_contents($apiURL);
        if(!$response) {
            die('No response');
        }
        $json = json_decode($response, true);
        $uniqueGroups = [];
        $uniqueCourses = [];

        foreach ($json as $item) {
            if(isset($item['group_name'])){
                $grupa = $item['group_name'];
                if (!in_array($grupa, $uniqueGroups)) {
                    $uniqueGroups[] = $grupa;
                }
            }
            if(isset($item['tok_name'])){
                $tok_name = $item['tok_name'];
                if (!in_array($tok_name, $uniqueCourses)) {
                    $uniqueCourses[] = $tok_name;
                }
            }
        }

        // Insert the student using the Student model
        $studentModel = new Student();
        $studentModel->setId($studentId);
        $studentModel->save();
        // Insert the group-student relationships using the GroupStudent model
        foreach($uniqueGroups as $group){
            $groupModel = new StudyGroup();
            $groupResult = $groupModel->findStudyGroup($group);
            if($groupResult){

                $groupStudentModel = new GroupStudent();
                $groupStudentModel->setGroupId($groupResult->getId());
                $groupStudentModel->setStudentId($studentId);
                $groupStudentModel->save();
            }
        }
        // Insert the course-student relationships using the StudyCourse model
        foreach($uniqueCourses as $course){
            $studyCourseModel = new StudyCourse();
            $studyCourseResult = $studyCourseModel->findAllStudyCourseByName($course);
            if($studyCourseResult){
                foreach($studyCourseResult as $courseTok){
                    $courseStudentModel = new CourseStudent();
                    $courseStudentModel->setCourseId($courseTok->getId());
                    $courseStudentModel->setStudentId($studentId);
                    $courseStudentModel->save();
                }
            }
        }
    }
}