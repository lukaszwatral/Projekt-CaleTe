<?php
namespace App\Service;
use App\Service\Config;
use App\Model\Department;
use App\Model\RoomBuilding;
use App\Model\GroupStudent;
use App\Model\Student;
use App\Model\StudyCourse;
use App\Model\StudyGroup;
use App\Model\Subject;
use App\Model\Teacher;

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
                $departShort = $item['wydz_sk'];
                $this->insertRoomBuilding($room, $depart, $departShort);
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
                $this->insertTeacher($firstName, $lastName, $title);
            }
            //Zajęcia
            if(isset($item['id'])){
                $id = $item['id'];
                $data_start = $item['start'];
                $data_koniec = $item['end'];
                $zastepca = $item['worker_cover'];
                if($zastepca == null){
                    $zastepca = "Brak";
                }
                $wykladowca = $item['worker'];
                $wydzial = $item['wydzial'];
                $grupa = $item['group_name'];
                $tok_studiow = $item['typ_sk'];
                if($tok_studiow == null){
                    $tok_studiow = "Brak";
                }
                $przedmiot = $item['subject'];
                $forma = $item['lesson_form'];
                $sala_budynek = $item['room'];
                $semestr = $item['semestr'];
                $this->insertZajecia($id, $data_start, $data_koniec, $zastepca, $wykladowca, $wydzial, $grupa, $tok_studiow, $przedmiot, $forma, $sala_budynek, $semestr);
            }
        }
    }

    private function insertDepartment(string $department, string $departmentShort){
        $departmentModel = new Department();
        $result = $departmentModel->findDepartment($department, $departmentShort);
        if(!$result) {
            $departmentModel->save();
        }
    }
    private function insertRoomBuilding(string $room, string $department, string $departmentShort){
        $departmentModel = new Department();
        $departmentResult = $departmentModel->findDepartment($department, $departmentShort);

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
    private function insertStudyCourse($tok_name, $shortType, $shortKind, $specialisation, $major){
        $studyCourseModel = new StudyCourse();
        $result = $studyCourseModel->findStudyCourse($tok_name, $shortType, $shortKind, $specialisation, $major);
        if(!$result){
            $studyCourseModel->save();
        }
    }
    private function insertSubject($subject, $form ,$tok_name, $shortType, $shortKind, $specialisation, $major){
        $studyCourseModel = new StudyCourse();
        $result = $studyCourseModel->findStudyCourse($tok_name, $shortType, $shortKind, $specialisation, $major);
        if($result){
            $studyCourseId = $result->getId();
            $subjectModel = new Subject();
            $result = $subjectModel->findSubject($subject, $form, $studyCourseId);
            if(!$result){
                $subjectModel->save();
            }
        }
    }
    private function insertStudyGroup(string $groupName){
        $studyGroupModel = new StudyGroup();
        $result = $studyGroupModel->findStudyGroup($groupName);
        if(!$result){
            $studyGroupModel->save();
        }
    }
    private function insertTeacher(string $firstName, string $lastName, string $title)
    {
        $teacherModel = new Teacher();
        $result = $teacherModel->findTeacher($firstName, $lastName, $title);
        if(!$result){
            $teacherModel->save();
        }
    }
    private function insertZajecia(int $id, string $data_start, string $data_koniec, string $zastepca, string $wykladowca, string $wydzial, string $grupa, string $tok_studiow, string $przedmiot, string $forma, string $sala_budynek, int $semestr)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM Wykladowca WHERE nazwisko_imie = :wykladowca");
        $stmt->bindParam(':wykladowca', $wykladowca);
        $stmt->execute();
        $result = $stmt->fetch();
        if($result){
            $wykladowca_id = $result['id'];
            $stmt = $this->pdo->prepare("SELECT id FROM Wydzial WHERE nazwa = :wydzial");
            $stmt->bindParam(':wydzial', $wydzial);
            $stmt->execute();
            $result = $stmt->fetch();
            if($result){
                $wydzial_id = $result['id'];
                $stmt = $this->pdo->prepare("SELECT id FROM Grupa WHERE nazwa = :grupa");
                $stmt->bindParam(':grupa', $grupa);
                $stmt->execute();
                $result = $stmt->fetch();
                if($result) {
                    $grupa_id = $result['id'];
                    $stmt = $this->pdo->prepare("SELECT id FROM Tok_studiow WHERE typ_skrot = :tok_studiow");
                    $stmt->bindParam(':tok_studiow', $tok_studiow);
                    $stmt->execute();
                    $result = $stmt->fetch();
                    if ($result) {
                        $tok_studiow_id = $result['id'];
                        $stmt = $this->pdo->prepare("SELECT id FROM Przedmiot WHERE nazwa = :przedmiot AND forma = :forma");
                        $stmt->bindParam(':przedmiot', $przedmiot);
                        $stmt->bindParam(':forma', $forma);
                        $stmt->execute();
                        $result = $stmt->fetch();
                        if($result){
                            $przedmiot_id = $result['id'];
                            $stmt = $this->pdo->prepare("SELECT id FROM Sala_z_budynkiem WHERE budynek_sala = :sala_budynek");
                            $stmt->bindParam(':sala_budynek', $sala_budynek);
                            $stmt->execute();
                            $result = $stmt->fetch();
                            if($result){
                                $sala_id = $result['id'];
                                $stmt = $this->pdo->prepare("SELECT id FROM Zajecia WHERE id = :id");
                                $stmt->bindParam(':id', $id);
                                $stmt->execute();
                                $result = $stmt->fetch();
                                if(!$result){
                                    $stmt = $this->pdo->prepare("INSERT INTO Zajecia (id,data_start, data_koniec, zastepca, semestr, wykladowca_id, wydzial_id, grupa_id, tok_studiow_id, przedmiot_id, sala_id) VALUES (:id,:data_start, :data_koniec, :zastepca, :semestr, :wykladowca_id, :wydzial_id, :grupa_id, :tok_studiow_id, :przedmiot_id, :sala_id)");
                                    $stmt->bindParam(':id', $id);
                                    $stmt->bindParam(':data_start', $data_start);
                                    $stmt->bindParam(':data_koniec', $data_koniec);
                                    $stmt->bindParam(':zastepca', $zastepca);
                                    $stmt->bindParam(':semestr', $semestr);
                                    $stmt->bindParam(':wykladowca_id', $wykladowca_id);
                                    $stmt->bindParam(':wydzial_id', $wydzial_id);
                                    $stmt->bindParam(':grupa_id', $grupa_id);
                                    $stmt->bindParam(':tok_studiow_id', $tok_studiow_id);
                                    $stmt->bindParam(':przedmiot_id', $przedmiot_id);
                                    $stmt->bindParam(':sala_id', $sala_id);
                                    $stmt->execute();
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    public function insertGrupaStudent(int $numer_albumu)
    {
        $apiURL = "https://plan.zut.edu.pl/schedule_student.php?number={$numer_albumu}&start=2024-10-1&end=2024-10-31";
        $response = file_get_contents($apiURL);
        if(!$response) {
            die('No response');
        }
        $json = json_decode($response, true);
        $uniqueGroups = [];

        foreach ($json as $item) {
            if(isset($item['group_name'])){
                $grupa = $item['group_name'];
                if (!in_array($grupa, $uniqueGroups)) {
                    $uniqueGroups[] = $grupa;
                }
            }
        }

        // Insert the student using the Student model
        $studentModel = new Student();
        $studentModel->setId($numer_albumu);
        $studentModel->save();

        // Insert the group-student relationships using the GroupStudent model
        foreach($uniqueGroups as $group){
            $groupModel = new StudyGroup();
            $groupResult = $groupModel->findStudyGroup($group);
            if($groupResult){
                $groupStudentModel = new GroupStudent();
                $groupStudentModel->setGroupId($groupResult->getId());
                $groupStudentModel->setStudentId($numer_albumu);
                $groupStudentModel->save();
            }
        }
    }
}