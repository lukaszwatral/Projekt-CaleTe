<?php
namespace App\Model;

use App\Service\Config;
use App\Service\Scrape;

class CourseStudent{
    private ?int $courseId = null;
    private ?int $studentId = null;

    public function getCourseId(): ?int{
        return $this->courseId;
    }

    public function setCourseId(?int $courseId): void{
        $this->courseId = $courseId;
    }

    public function getStudentId(): ?int{
        return $this->studentId;
    }

    public function setStudentId(?int $studentId): void{
        $this->studentId = $studentId;
    }

    public static function fromArray($array): CourseStudent{
        $courseStudent = new self();
        $courseStudent->fill($array);
        return $courseStudent;
    }

    public function fill($array): CourseStudent{
        if(isset($array['courseId']) && !$this->getCourseId()){
            $this->setCourseId($array['courseId']);
        }
        if(isset($array['studentId'])){
            $this->setStudentId($array['studentId']);
        }
        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM CourseStudent');
        $stmt->execute();
        $courseStudentsArray = $stmt->fetchAll();
        $courseStudents = [];
        foreach ($courseStudentsArray as $courseStudentArray) {
            $courseStudents[] = self::fromArray($courseStudentArray);
        }
        return $courseStudents;
    }

    public function findCourseStudent(int $courseId, int $studentId): ?CourseStudent
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM CourseStudent WHERE courseId = :courseId AND studentId = :studentId');
        $stmt->execute(['courseId' => $courseId, 'studentId' => $studentId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            return null; // No courseStudent found
        }
        return self::fromArray($result);
    }

    public function save(){
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('INSERT INTO CourseStudent (courseId, studentId) VALUES (:courseId, :studentId)');
        $stmt->execute(['courseId' => $this->getCourseId(), 'studentId' => $this->getStudentId()]);
    }
}