<?php
namespace App\Model;

use App\Service\Config;
use App\Service\Scrape;

class RoomBuilding{
    private ?int $id = null;
    private ?string $buildingRoom = null;
    private ?int $departmentId = null;
    public function getId(): ?int{
        return $this->id;
    }
    public function setId(?int $id): void{
        $this->id = $id;
    }

    public function getBuildingRoom(): ?string{
        return $this->buildingRoom;
    }
    public function setBuildingRoom(?string $buildingRoom): void{
        $this->buildingRoom = $buildingRoom;
    }

    public function getDepartmentId(): ?int{
        return $this->departmentId;
    }
    public function setDepartmentId(?int $departmentId): void{
        $this->departmentId = $departmentId;
    }

    public static function fromArray($array): RoomBuilding{
        $roomBuilding = new self();
        $roomBuilding->fill($array);
        return $roomBuilding;
    }

    public function fill($array): RoomBuilding{
        if(isset($array['id']) && !$this->getId()){
            $this->setId($array['id']);
        }
        if(isset($array['buildingRoom'])){
            $this->setBuildingRoom($array['buildingRoom']);
        }
        if(isset($array['departmentId'])){
            $this->setDepartmentId($array['departmentId']);
        }
        return $this;
    }

    public static function findAll(): array{
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM RoomBuilding');
        $stmt->execute();
        $roomsArray = $stmt->fetchAll();
        $rooms = [];
        foreach($roomsArray as $roomArray){
            $rooms[] = self::fromArray($roomArray);
        }
        return $rooms;
    }

    public function findRoom(string $room, int $departmentId): ?RoomBuilding{
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM RoomBuilding WHERE buildingRoom = :buildingRoom AND departmentId = :departmentId');
        $stmt->bindParam(':buildingRoom', $room);
        $stmt->bindParam(':departmentId', $departmentId);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            return null; // No department found
        }
        return self::fromArray($result);
    }

    public static function findRoomBuildingByName($name): array {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM RoomBuilding WHERE buildingRoom LIKE :buildingRoom');
        $stmt->execute(['buildingRoom' => '%' . $name . '%']);
        $roomsArray = $stmt->fetchAll();
        $rooms = [];
        foreach ($roomsArray as $roomArray) {
            $rooms[] = self::fromArray($roomArray);
        }
        return $rooms;
    }
    public function save(){
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));

        if(!$this->getId()){
            $stmt = $pdo->prepare('INSERT INTO RoomBuilding (buildingRoom, departmentId) VALUES (:buildingRoom, :departmentId)');
            $stmt->execute(['buildingRoom' => $this->getBuildingRoom(), 'departmentId' => $this->getDepartmentId()]);
            $this->setId((int)$pdo->lastInsertId());
        }
        else{
            $stmt = $pdo->prepare('UPDATE RoomBuilding SET buildingRoom = :buildingRoom, departmentId = :departmentId WHERE id = :id');
            $stmt->execute(['buildingRoom' => $this->getBuildingRoom(), 'departmentId' => $this->getDepartmentId(), 'id' => $this->getId()]);
        }
    }

    public function toArray(): array {
        return [
            'item' => $this->getBuildingRoom(),
        ];
    }
}