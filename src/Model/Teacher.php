<?php
namespace App\Model;
use App\Service\Config;
use App\Service\Scrape;

class Teacher{
    private ?int $id = null;
    private ?string $firstName = null;
    private ?string $lastName = null;
    private ?string $title = null;
    public function getId(): ?int{
        return $this->id;
    }
    public function setId(?int $id): void{
        $this->id = $id;
    }

    public function getFirstName(): ?string{
        return $this->firstName;
    }
    public function setFirstName(?string $firstName): void{
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string{
        return $this->lastName;
    }
    public function setLastName(?string $lastName): void{
        $this->lastName = $lastName;
    }

    public function getTitle(): ?string{
        return $this->title;
    }
    public function setTitle(?string $title): void{
        $this->title = $title;
    }

    public static function fromArray($array): Teacher{
        $teacher = new self();
        $teacher->fill($array);
        return $teacher;
    }

    public function fill($array): Teacher{
        if(isset($array['id']) && !$this->getId()){
            $this->setId($array['id']);
        }
        if(isset($array['firstName'])){
            $this->setFirstName($array['firstName']);
        }
        if(isset($array['lastName'])){
            $this->setLastName($array['lastName']);
        }
        if(isset($array['title'])){
            $this->setTitle($array['title']);
        }
        return $this;
    }

    public static function findAll(): array{
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM Teacher');
        $stmt->execute();
        $teachersArray = $stmt->fetchAll();
        $teachers = [];
        foreach($teachersArray as $teacherArray){
            $teachers[] = self::fromArray($teacherArray);
        }
        return $teachers;
    }

    public function findTeacher(string $firstName, string $lastName, ?string $title): ?Teacher{
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM Teacher WHERE firstName = :firstName AND lastName = :lastName AND title = :title');
        $stmt->execute(['firstName' => $firstName, 'lastName' => $lastName, 'title' => $title]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            return null; // No department found
        }
        return self::fromArray($result);
    }

    public static function findTeacherByName(string $fullName): array {
        $nameParts = explode(' ', $fullName);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM Teacher WHERE (firstName LIKE :fullName OR lastName LIKE :fullName)');
        $stmt->execute(['fullName' => '%' . $fullName . '%']);
        $teachersArray = $stmt->fetchAll();
        $teachers = [];
        foreach ($teachersArray as $teacherArray) {
            $teachers[] = self::fromArray($teacherArray);
        }
        return $teachers;
    }

    public static function findById(int $id): ?Teacher
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM Teacher WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            return null;
        }
        return self::fromArray($result);
    }

    public function save(){
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if(!$this->getId()){
            $stmt = $pdo->prepare('INSERT INTO Teacher (firstName, lastName, title) VALUES (:firstName, :lastName, :title)');
            $stmt->execute(['firstName' => $this->getFirstName(), 'lastName' => $this->getLastName(), 'title' => $this->getTitle()]);
            $this->setId((int)$pdo->lastInsertId());
        }
        else{
            $stmt = $pdo->prepare('UPDATE Teacher SET firstName = :firstName, lastName = :lastName, title = :title WHERE id = :id');
            $stmt->execute(['firstName' => $this->getFirstName(), 'lastName' => $this->getLastName(), 'title' => $this->getTitle(), 'id' => $this->getId()]);
        }
    }

    public function toArray() {
        return [
            'item' => $this->getFirstName() . ' ' . $this->getLastName(),
        ];
    }
}