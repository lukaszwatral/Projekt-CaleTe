<?php
namespace App\Model;

use App\Service\Config;
use App\Service\Scrape;

class Subject{
    private ?int $id = null;
    private ?string $name = null;
    private ?string $form = null;
    private ?string $kindShort = null;
    private ?int $studyCourseId = null;

    public function getId(): ?int{
        return $this->id;
    }
    public function setId(?int $id): void{
        $this->id = $id;
    }

    public function getName(): ?string{
        return $this->name;
    }
    public function setName(?string $name): void{
        $this->name = $name;
    }

    public function getForm(): ?string{
        return $this->form;
    }
    public function setForm(?string $form): void{
        $this->form = $form;
    }

    public function getKindShort(): ?string{
        return $this->kindShort;
    }
    public function setKindShort(?string $kindShort): void{
        $this->kindShort = $kindShort;
    }

    public function getStudyCourseId(): ?int{
        return $this->studyCourseId;
    }
    public function setStudyCourseId(?int $studyCourseId): void{
        $this->studyCourseId = $studyCourseId;
    }


    public static function fromArray($array): Subject{
        $subject = new self();
        $subject->fill($array);
        return $subject;
    }

    public function fill($array): Subject{
        if(isset($array['id']) && !$this->getId()){
            $this->setId($array['id']);
        }
        if(isset($array['name'])){
            $this->setName($array['name']);
        }
        if(isset($array['form'])){
            $this->setForm($array['form']);
        }
        if(isset($array['kindShort'])){
            $this->setKindShort($array['kindShort']);
        }
        if(isset($array['studyCourseId'])){
            $this->setStudyCourseId($array['studyCourseId']);
        }
        return $this;
    }

    public static function findAll(): array{
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM Subject');
        $stmt->execute();
        $subjectsArray = $stmt->fetchAll();
        $subjects = [];
        foreach($subjectsArray as $subjectArray){
            $subjects[] = self::fromArray($subjectArray);
        }
        return $subjects;
    }

    public function findSubject(string $name, string $form, string $kindShort, int $studyCourseId): ?Subject{
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM Subject WHERE name = :name AND form = :form AND kindShort = :kindShort AND studyCourseId = :studyCourseId');
        $stmt->execute(['name' => $name, 'form' => $form, 'kindShort' => $kindShort, 'studyCourseId' => $studyCourseId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            return null; // No department found
        }
        return self::fromArray($result);
    }

    public function save(){
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if(!$this->getId()){
            $stmt = $pdo->prepare('INSERT INTO Subject (name, form, kindShort, studyCourseId) VALUES (:name, :form, :kindShort, :studyCourseId)');
            $stmt->execute(['name' => $this->getName(), 'form' => $this->getForm(), 'kindShort' => $this->getKindShort(),'studyCourseId' => $this->getStudyCourseId()]);
            $this->setId((int)$pdo->lastInsertId());
        }
        else{
            $stmt = $pdo->prepare('UPDATE Subject SET name = :name, form = :form, kindShort = :kindShort, studyCourseId = :studyCourseId WHERE id = :id');
            $stmt->execute(['name' => $this->getName(), 'form' => $this->getForm(), 'formShort' => $this->getKindShort(), 'studyCourseId' => $this->getStudyCourseId(), 'id' => $this->getId()]);
        }
    }

    public static function findSubjectByName($name=null): array{
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM Subject WHERE name LIKE :name');
        $stmt->execute(['name' => "%$name%"]);
        $subjectsArray = $stmt->fetchAll();
        $subjects = [];
        foreach($subjectsArray as $subjectArray){
            $subjects[] = self::fromArray($subjectArray);
        }
        return $subjects;
    }

    public function toArray(): array{
        return [
            'name' => $this->getName(),
            'form' => $this->getForm(),
            'kindShort' => $this->getKindShort(),
        ];
    }
}