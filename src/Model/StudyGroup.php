<?php
namespace App\Model;

use App\Service\Config;
use App\Service\Scrape;

class StudyGroup{
    private ?int $id = null;
    private ?string $name = null;

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

    public static function fromArray($array): StudyGroup{
        $studyGroup = new self();
        $studyGroup->fill($array);
        return $studyGroup;
    }

    public function fill($array): StudyGroup{
        if(isset($array['id']) && !$this->getId()){
            $this->setId($array['id']);
        }
        if(isset($array['name'])){
            $this->setName($array['name']);
        }
        return $this;
    }

    public static function findAll(): array{
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM StudyGroup');
        $stmt->execute();
        $studyGroupsArray = $stmt->fetchAll();
        $studyGroups = [];
        foreach($studyGroupsArray as $studyGroupArray){
            $studyGroups[] = self::fromArray($studyGroupArray);
        }
        return $studyGroups;
    }

    public function findStudyGroup(string $name): ?StudyGroup{
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM StudyGroup WHERE name LIKE = :name');
        $stmt->execute(['name' => $name]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            return null; // No department found
        }
        return self::fromArray($result);
    }


    public static function findStudyGroupByName(string $name): ?array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM StudyGroup WHERE name LIKE :name');
        $stmt->execute(['name' => "%$name%"]);
        $result = $stmt->fetchAll();
        if ($result === false) {
            return null; // No study group found
        }
        foreach ($result as $studyGroup) {
            $studyGroups[] = self::fromArray($studyGroup);
        }
        return $studyGroups;
    }

    public function save(){
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if(!$this->getId()){
            $stmt = $pdo->prepare('INSERT INTO StudyGroup (name) VALUES (:name)');
            $stmt->execute(['name' => $this->getName()]);
            $this->setId((int)$pdo->lastInsertId());
        }
        else{
            $stmt = $pdo->prepare('UPDATE StudyGroup SET name = :name WHERE id = :id');
            $stmt->execute(['name' => $this->getName(), 'id' => $this->getId()]);
        }
    }

    public function toArray() {
        return [
            'name' => $this->getName()
        ];
    }
}