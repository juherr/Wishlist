<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="kdo_personne")
 */
// TODO manage prefix
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id_personne")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", name="nom_personne")
     */
    private string $name;

    /**
     * @ORM\Column(type="integer", name="choix_illu")
     */
    private int $iconId;

    public function __construct(string $name, int $iconId, ?int $id = null)
    {
        if ($id !== null && $id <= 0) {
            throw new \InvalidArgumentException('Invalid id: ' . $id);
        }
        if ($iconId <= 0) {
            throw new \InvalidArgumentException('Invalid icon_id: ' . $iconId);
        }
        $this->id = $id;
        $this->name = $name;
        $this->iconId = $iconId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getIconId(): int
    {
        return $this->iconId;
    }

    public function setIconId(int $iconId): void
    {
        $this->iconId = $iconId;
    }
}
