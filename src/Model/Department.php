<?php
namespace App\Model;

use App\Service\Config;
use App\Service\Scrape;

class Department{
    private ?int $id = null;
    private ?string $name = null;
    private ?string $shortName = null;

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

    public function getShortName(): ?string{
        return $this->shortName;
    }
    public function setShortName(?string $shortName): void{
        $this->shortName = $shortName;
    }

    public static function fromArray($array): Department{
        $department = new self();
        $department->fill($array);
        return $department;
    }

    public function fill($array): Department{
        if(isset($array['id']) && !$this->getId()){
            $this->setId($array['id']);
        }
        if(isset($array['name'])){
            $this->setName($array['name']);
        }
        if(isset($array['shortName'])){
            $this->setShortName($array['shortName']);
        }
        return $this;
    }

    public static function findAll(): array{
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM Department');
        $stmt->execute();
        $departmentsArray = $stmt->fetchAll();
        $departments = [];
        foreach($departmentsArray as $departmentArray){
            $departments[] = self::fromArray($departmentArray);
        }
        return $departments;
    }

    public function findDepartment(string $name): ?Department {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM Department WHERE name = :name');
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            return null; // No department found
        }
        return self::fromArray($result);
    }

    public function save(){
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if(!$this->getId()){
            $stmt = $pdo->prepare('INSERT INTO Department (name, shortName) VALUES (:name, :shortName)');
            $stmt->execute(['name' => $this->getName(), 'shortName' => $this->getShortName()]);
            $this->setId($pdo->lastInsertId());
        }
        else{
            $stmt = $pdo->prepare('UPDATE Department SET name = :name, shortName = :shortName WHERE id = :id');
            $stmt->execute(['name' => $this->getName(), 'shortName' => $this->getShortName(), 'id' => $this->getId()]);
        }
    }
}