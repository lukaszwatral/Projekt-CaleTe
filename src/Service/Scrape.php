<?php
namespace App\Service;
use App\Model\Department;
use App\Model\RoomBuilding;
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
                $department = $item['wydzial'];
                $this->insertRoomBuilding($room, $department);
            }
            //Tok studiów
            if(isset($item['typ_sk']) || isset($item['rodzaj_sk'])){
                $typ_sk = $item['typ_sk'];
                if($typ_sk == null){
                    $typ_sk = "Brak";
                }
                $tryb_sk = $item['rodzaj_sk'];
                if($tryb_sk == null){
                    $tryb_sk = "Brak";
                }
                $tryb = $item['rodzaj'];
                if ($tryb == null){
                    $tryb = "Brak";
                }
                $typ = $item['typ'];
                if($typ == null){
                    $typ = "Brak";
                }
                $this->insertTokStudiow($typ_sk, $tryb_sk, $tryb, $typ);
            }
            else{
                $typ_sk = "Brak";
                $tryb_sk = "Brak";
                $tryb = "Brak";
                $typ = "Brak";
                $this->insertTokStudiow($typ_sk, $tryb_sk, $tryb, $typ);
            }
            //Przedmiot
            if(isset($item['subject'])){
                $przedmiot = $item['subject'];
                $forma = $item['lesson_form'];
                $tryb = $item['rodzaj_sk'];
                if($tryb == null){
                    $tryb = "Brak";
                }
                $typ = $item['typ_sk'];
                if($typ == null){
                    $typ = "Brak";
                }
                $this->insertPrzedmiot($przedmiot, $tryb, $typ, $forma);
            }
            //Grupa
            if(isset($item['group_name'])){
                $grupa = $item['group_name'];
                $this->insertGrupa($grupa);
            }
            //Wykładowca
            if(isset($item['worker'])){
                $wykladowca = $item['worker'];
                $this->insertWykladowca($wykladowca);
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

    private function insertDepartment(string $wydzial, string $wydz_sk){
        $departmentModel = new Department();
        $result = $departmentModel->findDepartment($wydzial, $wydz_sk);
        if(!$result) {
            $departmentModel->insert($wydzial, $wydz_sk);
        }
    }
    private function insertRoomBuilding(string $room, string $department){
        $roomBuildingModel = new RoomBuilding();
        $result = $roomBuildingModel->findRoom($room, $department);
        if(!$result) {
            $roomBuildingModel->insert($room, $department);
        }
    }
    private function insertTokStudiow(string $typ_sk, string $tryb_sk, string $tryb, string $typ)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM Tok_studiow WHERE typ = :typ AND tryb = :tryb");
        $stmt->bindParam(':typ', $typ);
        $stmt->bindParam(':tryb', $tryb);
        $stmt->execute();
        $result = $stmt->fetch();

        if(!$result){
            $stmt = $this->pdo->prepare("INSERT INTO Tok_studiow (typ, tryb, typ_skrot, tryb_skrot) VALUES (:typ, :tryb, :typ_sk, :tryb_sk)");
            $stmt->bindParam(':typ', $typ);
            $stmt->bindParam(':tryb', $tryb);
            $stmt->bindParam(':typ_sk', $typ_sk);
            $stmt->bindParam(':tryb_sk', $tryb_sk);
            $stmt->execute();
        }
    }
    private function insertPrzedmiot(string $przedmiot, string $tryb, string $typ, string $forma)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM Tok_studiow WHERE tryb_skrot = :tryb AND typ_skrot = :typ");
        $stmt->bindParam(':tryb', $tryb);
        $stmt->bindParam(':typ', $typ);
        $stmt->execute();
        $result = $stmt->fetch();
        if($result){
            $tok_id = $result['id'];
            $stmt = $this->pdo->prepare("SELECT id FROM Przedmiot WHERE nazwa = :przedmiot AND forma = :forma AND tok_studiow_id = :tok_id");
            $stmt->bindParam(':przedmiot', $przedmiot);
            $stmt->bindParam(':forma', $forma);
            $stmt->bindParam(':tok_id', $tok_id);
            $stmt->execute();
            $result = $stmt->fetch();
            if(!$result){
                $stmt = $this->pdo->prepare("INSERT INTO Przedmiot (nazwa, forma, tok_studiow_id) VALUES (:przedmiot, :forma, :tok_id)");
                $stmt->bindParam(':przedmiot', $przedmiot);
                $stmt->bindParam(':forma', $forma);
                $stmt->bindParam(':tok_id', $tok_id);
                $stmt->execute();
            }
        }
        else{
            echo $przedmiot;
            throw new \Exception("Przedmiot: Tok_studiow not found");
        }

    }
    private function insertGrupa(string $grupa)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM Grupa WHERE nazwa = :grupa");
        $stmt->bindParam(':grupa', $grupa);
        $stmt->execute();
        $result = $stmt->fetch();

        if(!$result){
            $stmt = $this->pdo->prepare("INSERT INTO Grupa (nazwa) VALUES (:grupa)");
            $stmt->bindParam(':grupa', $grupa);
            $stmt->execute();
        }
    }
    private function insertWykladowca(string $wykladowca)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM Wykladowca WHERE nazwisko_imie = :wykladowca");
        $stmt->bindParam(':wykladowca', $wykladowca);
        $stmt->execute();
        $result = $stmt->fetch();

        if(!$result){
            $stmt = $this->pdo->prepare("INSERT INTO Wykladowca (nazwisko_imie) VALUES (:wykladowca)");
            $stmt->bindParam(':wykladowca', $wykladowca);
            $stmt->execute();
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

        $stmt = $this->pdo->prepare("INSERT INTO Student (id) VALUES (:numer_albumu)");
        $stmt->bindParam(':numer_albumu', $numer_albumu);
        $stmt->execute();

        foreach($uniqueGroups as $group){
            $stmt = $this->pdo->prepare("SELECT id FROM Grupa WHERE nazwa = :grupa");
            $stmt->bindParam(':grupa', $group);
            $stmt->execute();
            $result = $stmt->fetch();
            if($result){
                $stmt = $this->pdo->prepare("INSERT INTO Grupa_Student (grupa_id,student_id) VALUES (:grupa,:student)");
                $stmt->bindParam(':grupa', $result['id']);
                $stmt->bindParam(':student', $numer_albumu);
                $stmt->execute();
            }
        }
    }
}