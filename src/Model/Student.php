<?php
namespace App\Model;

use App\Service\Config;
use App\Service\Scrape;

class Student{
    private ?int $id = null;
    private ?int $studyCourseId = null;
    private ?int $departmentId = null;

    public function getId(): ?int{
        return $this->id;
    }
    public function setId(?int $id): void{
        $this->id = $id;
    }

    public function getStudyCourseId(): ?int{
        return $this->studyCourseId;
    }
    public function setStudyCourseId(?int $studyCourseId): void{
        $this->studyCourseId = $studyCourseId;
    }

    public function getDepartmentId(): ?int{
        return $this->departmentId;
    }
    public function setDepartmentId(?int $departmentId): void{
        $this->departmentId = $departmentId;
    }

    public static function fromArray($array): Student{
        $student = new self();
        $student->fill($array);
        return $student;
    }

    public function fill($array): Student{
        if(isset($array['id']) && !$this->getId()){
            $this->setId($array['id']);
        }
        if(isset($array['studyCourseId'])){
            $this->setStudyCourseId($array['studyCourseId']);
        }
        if(isset($array['departmentId'])){
            $this->setDepartmentId($array['departmentId']);
        }
        return $this;
    }

    public static function findAll(): array{
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM Student');
        $stmt->execute();
        $studentsArray = $stmt->fetchAll();
        $students = [];
        foreach ($studentsArray as $studentArray) {
            $students[] = self::fromArray($studentArray);
        }
        return $students;
    }

    public function findStudent(int $id): ?Student{
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM Student WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            return null; // No department found
        }
        return self::fromArray($result);
    }

    public function save(){
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if(!$this->getId()){
            $stmt = $pdo->prepare('INSERT INTO Student (studyCourseId, departmentId) VALUES (:studyCourseId, :departmentId)');
            $stmt->execute([
                'studyCourseId' => $this->getStudyCourseId(),
                'departmentId' => $this->getDepartmentId()
            ]);
            $this->setId((int)$pdo->lastInsertId());
        }
        else{
            $stmt = $pdo->prepare('UPDATE Student SET studyCourseId = :studyCourseId, departmentId = :departmentId WHERE id = :id');
            $stmt->execute([
                'studyCourseId' => $this->getStudyCourseId(),
                'departmentId' => $this->getDepartmentId(),
                'id' => $this->getId()
            ]);
        }
    }
}