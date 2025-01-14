<?php
namespace App\Model;

use App\Service\Config;
use App\Service\Scrape;

class Lesson{
    private ?int $id = null;
    private ?string $dateStart = null;
    private ?string $dateEnd = null;
    private ?string $teacherCover = null;
    private ?int $semester = null;
    private ?int $teacherId = null;
    private ?int $departmentId = null;
    private ?int $groupId = null;
    private ?int $studyCourseId = null;
    private ?int $subjectId = null;
    private ?int $roomId = null;

    public function getId(): ?int{
        return $this->id;
    }
    public function setId(?int $id): void{
        $this->id = $id;
    }

    public function getDateStart(): ?string{
        return $this->dateStart;
    }
    public function setDateStart(?string $dateStart): void{
        $this->dateStart = $dateStart;
    }

    public function getDateEnd(): ?string{
        return $this->dateEnd;
    }
    public function setDateEnd(?string $dateEnd): void{
        $this->dateEnd = $dateEnd;
    }

    public function getTeacherCover(): ?string{
        return $this->teacherCover;
    }
    public function setTeacherCover(?string $teacherCover): void{
        $this->teacherCover = $teacherCover;
    }

    public function getSemester(): ?int{
        return $this->semester;
    }
    public function setSemester(?int $semester): void{
        $this->semester = $semester;
    }

    public function getTeacherId(): ?int{
        return $this->teacherId;
    }
    public function setTeacherId(?int $teacherId): void{
        $this->teacherId = $teacherId;
    }

    public function getDepartmentId(): ?int{
        return $this->departmentId;
    }
    public function setDepartmentId(?int $departmentId): void{
        $this->departmentId = $departmentId;
    }

    public function getGroupId(): ?int{
        return $this->groupId;
    }
    public function setGroupId(?int $groupId): void{
        $this->groupId = $groupId;
    }

    public function getStudyCourseId(): ?int{
        return $this->studyCourseId;
    }
    public function setStudyCourseId(?int $studyCourseId): void{
        $this->studyCourseId = $studyCourseId;
    }

    public function getSubjectId(): ?int{
        return $this->subjectId;
    }
    public function setSubjectId(?int $subjectId): void{
        $this->subjectId = $subjectId;
    }

    public function getRoomId(): ?int{
        return $this->roomId;
    }
    public function setRoomId(?int $roomId): void{
        $this->roomId = $roomId;
    }

    public static function fromArray($array): Lesson{
        $lesson = new self();
        $lesson->fill($array);
        return $lesson;
    }

    public function fill($array): Lesson{
        if(isset($array['id']) && !$this->getId()){
            $this->setId($array['id']);
        }
        if(isset($array['dateStart'])){
            $this->setDateStart($array['dateStart']);
        }
        if(isset($array['dateEnd'])){
            $this->setDateEnd($array['dateEnd']);
        }
        if(isset($array['teacherCover'])){
            $this->setTeacherCover($array['teacherCover']);
        }
        if(isset($array['semester'])){
            $this->setSemester($array['semester']);
        }
        if(isset($array['teacherId'])){
            $this->setTeacherId($array['teacherId']);
        }
        if(isset($array['departmentId'])){
            $this->setDepartmentId($array['departmentId']);
        }
        if(isset($array['groupId'])){
            $this->setGroupId($array['groupId']);
        }
        if(isset($array['studyCourseId'])){
            $this->setStudyCourseId($array['studyCourseId']);
        }
        if(isset($array['subjectId'])){
            $this->setSubjectId($array['subjectId']);
        }
        if(isset($array['roomId'])){
            $this->setRoomId($array['roomId']);
        }
        return $this;
    }

    public static function findAll(): array{
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM Lesson');
        $stmt->execute();
        $lessonsArray = $stmt->fetchAll();
        $lessons = [];
        foreach($lessonsArray as $lessonArray){
            $lessons[] = self::fromArray($lessonArray);
        }
        return $lessons;
    }

    public function findLesson(int $id): ?Lesson{
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM Lesson WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            return null; // No lesson found
        }
        return self::fromArray($result);
    }

    public function save(){
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $existingLesson = $this->findLesson($this->getId());

        if (!$existingLesson) {
            $stmt = $pdo->prepare('INSERT INTO Lesson (id, dateStart, dateEnd, teacherCover, semester, teacherId, departmentId, groupId, studyCourseId, subjectId, roomId) VALUES (:id, :dateStart, :dateEnd, :teacherCover, :semester, :teacherId, :departmentId, :groupId, :studyCourseId, :subjectId, :roomId)');
        } else {
            $stmt = $pdo->prepare('UPDATE Lesson SET dateStart = :dateStart, dateEnd = :dateEnd, teacherCover = :teacherCover, semester = :semester, teacherId = :teacherId, departmentId = :departmentId, groupId = :groupId, studyCourseId = :studyCourseId, subjectId = :subjectId, roomId = :roomId WHERE id = :id');
        }
        $stmt->execute([
            'id' => $this->getId(),
            'dateStart' => $this->getDateStart(),
            'dateEnd' => $this->getDateEnd(),
            'teacherCover' => $this->getTeacherCover(),
            'semester' => $this->getSemester(),
            'teacherId' => $this->getTeacherId(),
            'departmentId' => $this->getDepartmentId(),
            'groupId' => $this->getGroupId(),
            'studyCourseId' => $this->getStudyCourseId(),
            'subjectId' => $this->getSubjectId(),
            'roomId' => $this->getRoomId()
        ]);
    }
}