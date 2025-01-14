<?php
namespace App\Model;

use App\Service\Config;
use App\Service\Scrape;

class GroupStudent{
    private ?int $groupId = null;
    private ?int $studentId = null;

    public function getGroupId(): ?int{
        return $this->groupId;
    }
    public function setGroupId(?int $groupId): void{
        $this->groupId = $groupId;
    }

    public function getStudentId(): ?int{
        return $this->studentId;
    }
    public function setStudentId(?int $studentId): void{
        $this->studentId = $studentId;
    }

    public static function fromArray($array): GroupStudent{
        $groupStudent = new self();
        $groupStudent->fill($array);
        return $groupStudent;
    }

    public function fill($array): GroupStudent{
        if(isset($array['groupId']) && !$this->getGroupId()){
            $this->setGroupId($array['groupId']);
        }
        if(isset($array['studentId'])){
            $this->setStudentId($array['studentId']);
        }
        return $this;
    }

    public static function findAll(): array{
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM GroupStudent');
        $stmt->execute();
        $groupStudentsArray = $stmt->fetchAll();
        $groupStudents = [];
        foreach($groupStudentsArray as $groupStudentArray){
            $groupStudents[] = self::fromArray($groupStudentArray);
        }
        return $groupStudents;
    }

    public function findGroupStudent(int $groupId, int $studentId): ?GroupStudent{
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM GroupStudent WHERE groupId = :groupId AND studentId = :studentId');
        $stmt->execute(['groupId' => $groupId, 'studentId' => $studentId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            return null; // No department found
        }
        return self::fromArray($result);
    }

    public function save(){
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if(!$this->getGroupId() || !$this->getStudentId()){
            $stmt = $pdo->prepare('INSERT INTO GroupStudent (groupId, studentId) VALUES (:groupId, :studentId)');
            $stmt->execute(['groupId' => $this->getGroupId(), 'studentId' => $this->getStudentId()]);
        }
        else{
            $stmt = $pdo->prepare('UPDATE GroupStudent SET groupId = :groupId, studentId = :studentId WHERE groupId = :groupId AND studentId = :studentId');
            $stmt->execute(['groupId' => $this->getGroupId(), 'studentId' => $this->getStudentId()]);
        }
    }
}