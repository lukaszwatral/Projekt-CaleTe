<?php
namespace App\Model;

use App\Service\Config;

class Filter {
    private ?int $id = null;
    private ?string $wykladowca = null;
    private ?string $sala = null;
    private ?string $przedmiot = null;
    private ?string $grupa = null;
    private ?string $numer_albumu = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Filter
    {
        $this->id = $id;

        return $this;
    }

    public function getWykladowca(): ?string
    {
        return $this->wykladowca;
    }

    public function setWykladowca(?string $wykladowca): Filter
    {
        $this->wykladowca = $wykladowca;

        return $this;
    }

    public function getSala(): ?string
    {
        return $this->sala;
    }

    public function setSala(?string $sala): Filter
    {
        $this->sala = $sala;

        return $this;
    }

    public function getPrzedmiot(): ?string
    {
        return $this->przedmiot;
    }

    public function setPrzedmiot(?string $przedmiot): Filter
    {
        $this->przedmiot = $przedmiot;

        return $this;
    }

    public function getGrupa(): ?string
    {
        return $this->grupa;
    }

    public function setGrupa(?string $grupa): Filter
    {
        $this->grupa = $grupa;

        return $this;
    }

    public function getNumerAlbumu(): ?string
    {
        return $this->numer_albumu;
    }

    public function setNumerAlbumu(?string $numer_albumu): Filter
    {
        $this->numer_albumu = $numer_albumu;

        return $this;
    }

    public static function fAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM Wydzial';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $posts = [];
        $postsArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($postsArray as $postArray) {
            $posts[] = self::fromArray($postArray);
        }

        return $posts;
    }

    public static function fromArray($array): Filter
    {
        $post = new self();
        $post->fill($array);

        return $post;
    }

    public function fill($array): Filter
{
    if (isset($array['id']) && ! $this->getId()) {
        $this->setId($array['id']);
    }
    if (isset($array['wykladowca'])) {
        $this->setWykladowca($array['wykladowca']);
    }
    if (isset($array['sala'])) {
        $this->setSala($array['sala']);
    }
    if (isset($array['przedmiot'])) {
        $this->setPrzedmiot($array['przedmiot']);
    }
    if (isset($array['grupa'])) {
        $this->setGrupa($array['grupa']);
    }
    if (isset($array['numer_albumu'])) {
        $this->setNumerAlbumu($array['numer_albumu']);
    }

    return $this;
}

}
