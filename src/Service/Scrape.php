<?php
namespace App\Service;
use App\Service\Config;

class ScrapeData
{
    //@todo Pamiętaj o ustawieniu PDO jak klase tworzysz
    private $pdo;
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @throws \Exception
     */
    public function scrapeData(string $department){
        $apiURL = "https://plan.zut.edu.pl/schedule_student.php?kind=apiwi&department={$department}&start=2024-10-7&end=2024-10-8";
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
                $this->insertWydzial($departValue, $departShortValue);
            }
            //Sala z budynkiem
            if(isset($item['room'])){
                $room = $item['room'];
                $wydzial = $item['wydzial'];
                $this->insertSalaBudynek($room, $wydzial);
            }
            //Tok studiów
            if(isset($item['typ_sk']) && isset($item['rodzaj_sk'])){
                $typ_sk = $item['typ_sk'];
                $tryb_sk = $item['rodzaj_sk'];
                $tryb = $item['rodzaj'];
                $typ = $item['typ'];
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
                $tryb = $item['rodzaj_sk'];
                if($tryb == null){
                    $tryb = "Brak";
                }
                $typ = $item['typ_sk'];
                if($typ == null){
                    $typ = "Brak";
                }
                $this->insertPrzedmiot($przedmiot, $tryb, $typ);
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
                $this->insertZajecia($id, $data_start, $data_koniec, $zastepca, $wykladowca, $wydzial, $grupa, $tok_studiow);
            }
        }
    }

    private function insertWydzial(string $wydzial, string $wydz_sk)
    {
        // Check if department already exist
        $stmt = $this->pdo->prepare("SELECT id FROM Wydzial WHERE nazwa = :wydzial AND skrot = :wydz_sk");
        $stmt->bindParam(':wydzial', $wydzial);
        $stmt->bindParam(':wydz_sk', $wydz_sk);
        $stmt->execute();
        $result = $stmt->fetch();

        if (!$result) {
            // Insert new department if not exist
            $stmt = $this->pdo->prepare("INSERT INTO Wydzial (nazwa, skrot) VALUES (:wydzial, :wydz_sk)");
            $stmt->bindParam(':wydzial', $wydzial);
            $stmt->bindParam(':wydz_sk', $wydz_sk);
            $stmt->execute();
        }
    }
    private function insertSalaBudynek(string $room, string $wydzial){

        $stmt = $this->pdo->prepare("SELECT id FROM Wydzial WHERE nazwa = :wydzial");
        $stmt->bindParam(':wydzial', $wydzial);
        $stmt->execute();
        $result = $stmt->fetch();

        if($result){
            $wydzial_id = $result['id'];
            $stmt = $this->pdo->prepare("SELECT id FROM Sala_z_budynkiem WHERE budynek_sala = :room AND wydzial_id = :wydzial_id");
            $stmt->bindParam(':room', $room);
            $stmt->bindParam(':wydzial_id', $wydzial_id);
            $stmt->execute();
            $result = $stmt->fetch();
            if(!$result){
                $stmt = $this->pdo->prepare("INSERT INTO Sala_z_budynkiem (budynek_sala, wydzial_id) VALUES (:room, :wydzial_id)");
                $stmt->bindParam(':room', $room);
                $stmt->bindParam(':wydzial_id', $wydzial_id);
                $stmt->execute();
            }
        }
        else{
            throw new \Exception("Department not found");
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
    private function insertPrzedmiot(string $przedmiot, string $tryb, string$typ)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM Tok_studiow WHERE tryb_skrot = :tryb AND typ_skrot = :typ");
        $stmt->bindParam(':tryb', $tryb);
        $stmt->bindParam(':typ', $typ);
        $stmt->execute();
        $result = $stmt->fetch();

        if($result){
            $tok_id = $result['id'];
            $stmt = $this->pdo->prepare("SELECT id FROM Przedmiot WHERE nazwa = :przedmiot AND tok_studiow_id = :tok_id");
            $stmt->bindParam(':przedmiot', $przedmiot);
            $stmt->bindParam(':tok_id', $tok_id);
            $stmt->execute();
            $result = $stmt->fetch();
            if(!$result){
                $stmt = $this->pdo->prepare("INSERT INTO Przedmiot (nazwa, tok_studiow_id) VALUES (:przedmiot, :tok_id)");
                $stmt->bindParam(':przedmiot', $przedmiot);
                $stmt->bindParam(':tok_id', $tok_id);
                $stmt->execute();
            }
        }
        else{
            throw new \Exception("Tok_studiow not found");
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
    private function insertZajecia(int $id, string $data_start, string $data_koniec, string $zastepca, string $wykladowca, string $wydzial, string $grupa, string $tok_studiow)
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
                        $stmt = $this->pdo->prepare("SELECT id FROM Zajecia WHERE id = :id");
                        $stmt->bindParam(':id', $id);
                        $stmt->execute();
                        $result = $stmt->fetch();
                        if(!$result){
                            $stmt = $this->pdo->prepare("INSERT INTO Zajecia (id,data_start, data_koniec, zastepca, wykladowca_id, wydzial_id, grupa_id, tok_studiow_id) VALUES (:id,:data_start, :data_koniec, :zastepca, :wykladowca_id, :wydzial_id, :grupa_id, :tok_studiow_id)");
                            $stmt->bindParam(':id', $id);
                            $stmt->bindParam(':data_start', $data_start);
                            $stmt->bindParam(':data_koniec', $data_koniec);
                            $stmt->bindParam(':zastepca', $zastepca);
                            $stmt->bindParam(':wykladowca_id', $wykladowca_id);
                            $stmt->bindParam(':wydzial_id', $wydzial_id);
                            $stmt->bindParam(':grupa_id', $grupa_id);
                            $stmt->bindParam(':tok_studiow_id', $tok_studiow_id);
                            $stmt->execute();
                        }
                    }
                }
            }
        }
    }
    private function insertGrupaStudent(int $numer_albumu, string $grupa)
    {
        //Sprawdzanie czy student o tym numerze albumu istnieje
        $stmt = $this->pdo->prepare("SELECT id FROM Student WHERE id = :numer_albumu");
        $stmt->bindParam(':numer_albumu', $numer_albumu);
        $stmt->execute();
        $result = $stmt->fetch();
        //Jeżeli nie to dodanie studenta
        if(!$result){
            $stmt = $this->pdo->prepare("INSERT INTO Student (id) VALUES (:numer_albumu)");
            $stmt->bindParam(':numer_albumu', $numer_albumu);
            $stmt->execute();
        }
    }
}