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

    public function findDepartment(string $name, string $shortName): ?Department{
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT id FROM Department WHERE name = :name AND shortName = :shortName');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':shortName', $shortName);
        $stmt->execute();
        return $stmt->fetch() ? self::fromArray($stmt->fetch()) : null;
    }

    public function insert(string $name, string $shortName){
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('INSERT INTO Department (name, shortName) VALUES (:name, :shortName)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':shortName', $shortName);
        $stmt->execute();
    }
}