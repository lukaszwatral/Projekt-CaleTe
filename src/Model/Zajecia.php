<?php
namespace App\Model;

use App\Service\Config;

class Zajecia {
    private ?int $id = null;
    private ?int $grupaId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Zajecia
    {
        $this->id = $id;

        return $this;
    }

    public function getGrupaId(): ?int
    {
        return $this->grupaId;
    }

    public function setGrupaId(?int $grupaId): Zajecia
    {
        $this->grupaId = $grupaId;

        return $this;
    }

    public static function fromArray($array): Zajecia
    {
        $zajecia = new self();
        $zajecia->fill($array);

        return $zajecia;
    }

    public function fill($array): Zajecia
    {
        if (isset($array['id']) && ! $this->getId()) {
            $this->setId($array['id']);
        }
        if (isset($array['grupa_id'])) {
            $this->setGrupaId($array['grupa_id']);
        }
        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM Zajecia';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $zajecia = [];
        $zajeciaArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($zajeciaArray as $zajArray) {
            $zajecia[] = self::fromArray($zajArray);
        }

        return $zajecia;
    }

    public static function find($id): ?Zajecia
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM Zajecia WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);

        $zajeciaArray = $statement->fetch(\PDO::FETCH_ASSOC);
        if (! $zajeciaArray) {
            return null;
        }
        $zajecia = Zajecia::fromArray($zajeciaArray);

        return $zajecia;
    }

   public static function filteredFind($nazwaGrupy): array
{
    $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
    $sql = 'SELECT id FROM Grupa WHERE nazwa = :nazwaGrupy';
    $statement = $pdo->prepare($sql);
    $statement->execute(['nazwaGrupy' => $nazwaGrupy]);
    $grupaIdArray = $statement->fetch(\PDO::FETCH_ASSOC);
    if (!$grupaIdArray) {
        return [];
    }

    $grupaId = $grupaIdArray['id'];
    $sql = 'SELECT * FROM Zajecia WHERE grupa_id = :grupaId';
    $statement = $pdo->prepare($sql);
    $statement->execute(['grupaId' => $grupaId]);

    $zajecia = [];
    $zajeciaArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
    foreach ($zajeciaArray as $zajArray) {
        $zajecia[] = self::fromArray($zajArray);
    }

    return $zajecia;
}
}