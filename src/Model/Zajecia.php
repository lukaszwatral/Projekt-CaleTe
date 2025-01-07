<?php
namespace App\Model;

use App\Service\Config;

class Zajecia {
    private ?int $id = null;
    private ?string $data_start = null;
    private ?string $data_koniec = null;
    private ?string $zastepca = null;
    private ?int $wykladowca_id = null;
    private ?int $wydzial_id = null;
    private ?int $grupa_id = null;
    private ?int $tok_studiow_id = null;
    private ?int $przedmiot_id = null;
    private ?int $sala_id = null;
    private ?int $semestr = null;
    private ?int $student_id = null;

    public function getId(): ?int
{
    return $this->id;
}

public function setId(?int $id): void
{
    $this->id = $id;
}

public function getDataStart(): ?string
{
    return $this->data_start;
}

public function setDataStart(?string $data_start): void
{
    $this->data_start = $data_start;
}

public function getDataKoniec(): ?string
{
    return $this->data_koniec;
}

public function setDataKoniec(?string $data_koniec): void
{
    $this->data_koniec = $data_koniec;
}

public function getZastepca(): ?string
{
    return $this->zastepca;
}

public function setZastepca(?string $zastepca): void
{
    $this->zastepca = $zastepca;
}

public function getWykladowcaId(): ?int
{
    return $this->wykladowca_id;
}

public function setWykladowcaId(?int $wykladowca_id): void
{
    $this->wykladowca_id = $wykladowca_id;
}

public function getWydzialId(): ?int
{
    return $this->wydzial_id;
}

public function setWydzialId(?int $wydzial_id): void
{
    $this->wydzial_id = $wydzial_id;
}

public function getGrupaId(): ?int
{
    return $this->grupa_id;
}

public function setGrupaId(?int $grupa_id): void
{
    $this->grupa_id = $grupa_id;
}

public function getTokStudiowId(): ?int
{
    return $this->tok_studiow_id;
}

public function setTokStudiowId(?int $tok_studiow_id): void
{
    $this->tok_studiow_id = $tok_studiow_id;
}

public function getPrzedmiotId(): ?int
{
    return $this->przedmiot_id;
}

public function setPrzedmiotId(?int $przedmiot_id): void
{
    $this->przedmiot_id = $przedmiot_id;
}

public function getSalaId(): ?int
{
    return $this->sala_id;
}

public function setSalaId(?int $sala_id): void
{
    $this->sala_id = $sala_id;
}

public function getSemestr(): ?int
{
    return $this->semestr;
}

public function setSemestr(?int $semestr): void
{
    $this->semestr = $semestr;
}

public function getStudentId(): ?int
{
    return $this->student_id;
}

public function setStudentId(?int $student_id): void
{
    $this->student_id = $student_id;
}


    public static function fromArray($array): Zajecia
    {
        $zajecia = new self();
        $zajecia->fill($array);

        return $zajecia;
    }

    public function fill($array): Zajecia
    {
        if (isset($array['id']) && ! $this->getId()) {
            $this->setId($array['id']);
        }
        if (isset($array['data_start'])) {
            $this->setDataStart($array['data_start']);
        }
        if (isset($array['data_koniec'])) {
            $this->setDataKoniec($array['data_koniec']);
        }
        if (isset($array['zastepca'])) {
            $this->setZastepca($array['zastepca']);
        }
        if (isset($array['wykladowca_id'])) {
            $this->setWykladowcaId($array['wykladowca_id']);
        }
        if (isset($array['wydzial_id'])) {
            $this->setWydzialId($array['wydzial_id']);
        }
        if (isset($array['grupa_id'])) {
            $this->setGrupaId($array['grupa_id']);
        }
        if (isset($array['tok_studiow_id'])) {
            $this->setTokStudiowId($array['tok_studiow_id']);
        }
        if (isset($array['przedmiot_id'])) {
            $this->setPrzedmiotId($array['przedmiot_id']);
        }
        if (isset($array['sala_id'])) {
            $this->setSalaId($array['sala_id']);
        }
        if (isset($array['semestr'])) {
            $this->setSemestr($array['semestr']);
        }
        if (isset($array['student_id'])) {
            $this->setStudentId($array['student_id']);
        }
        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM Zajecia';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $zajecia = [];
        $zajeciaArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($zajeciaArray as $zajArray) {
            $zajecia[] = self::fromArray($zajArray);
        }

        return $zajecia;
    }

    public static function find($id): ?Zajecia
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM Zajecia WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);

        $zajeciaArray = $statement->fetch(\PDO::FETCH_ASSOC);
        if (! $zajeciaArray) {
            return null;
        }
        $zajecia = Zajecia::fromArray($zajeciaArray);

        return $zajecia;
    }

    public static function filteredFind($wykladowca = null, $przedmiot = null, $sala = null, $grupa = null, $wydzial = null, $forma_przedmiotu = null, $semestr_studiow = null, $rok_studiow = null): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));

        $sql = 'SELECT Zajecia.*,
                       Wykladowca.nazwisko_imie AS wykladowca_name,
                       Przedmiot.nazwa AS przedmiot_name,
                       Sala_z_budynkiem.budynek_sala AS sala_name,
                       Grupa.nazwa AS grupa_name,
                       Wydzial.nazwa AS wydzial_name
                FROM Zajecia
                LEFT JOIN Wykladowca ON Zajecia.wykladowca_id = Wykladowca.id
                LEFT JOIN Przedmiot ON Zajecia.przedmiot_id = Przedmiot.id
                LEFT JOIN Sala_z_budynkiem ON Zajecia.sala_id = Sala_z_budynkiem.id
                LEFT JOIN Grupa ON Zajecia.grupa_id = Grupa.id
                LEFT JOIN Wydzial ON Zajecia.wydzial_id = Wydzial.id
                WHERE 1=1';

        $params = [];

        if ($wykladowca != null) {
            $sql .= ' AND Wykladowca.nazwisko_imie = :wykladowca';
            $params['wykladowca'] = $wykladowca;
        }
        if ($przedmiot != null) {
            $sql .= ' AND Przedmiot.nazwa = :przedmiot';
            $params['przedmiot'] = $przedmiot;
        }
        if ($sala != null) {
            $sql .= ' AND Sala_z_budynkiem.budynek_sala = :sala';
            $params['sala'] = $sala;
        }
        if ($grupa != null) {
            $sql .= ' AND Grupa.nazwa = :grupa';
            $params['grupa'] = $grupa;
        }
        if ($wydzial != null) {
            $sql .= ' AND Wydzial.nazwa = :wydzial';
            $params['wydzial'] = $wydzial;
        }
        if ($forma_przedmiotu != null) {
            $sql .= ' AND Zajecia.forma_przedmiotu = :forma_przedmiotu';
            $params['forma_przedmiotu'] = $forma_przedmiotu;
        }
        if ($semestr_studiow != null) {
            $sql .= ' AND Zajecia.semestr = :semestr_studiow';
            $params['semestr_studiow'] = $semestr_studiow;
        }
        if ($rok_studiow != null) {
            $sql .= ' AND Zajecia.rok_studiow = :rok_studiow';
            $params['rok_studiow'] = $rok_studiow;
        }

        $statement = $pdo->prepare($sql);
        $statement->execute($params);
        $zajeciaArray = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $zajecia = [];
        foreach ($zajeciaArray as $zajArray) {
            $zajecia[] = self::fromArray($zajArray);
        }

        return $zajecia;
    }
}