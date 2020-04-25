<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="kdo_users")
 */
// TODO manage prefix
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", name="name", length=30)
     */
    private string $name;

    /**
     * @ORM\Column(type="integer", name="icon_id")
     */
    private int $iconId;

    /**
     * @ORM\OneToMany(targetEntity="Gift", mappedBy="user", orphanRemoval=true, cascade={"remove"})
     */
    private Collection $gifts;

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
        $this->gifts = new ArrayCollection();
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

    /**
     * @return Collection|Gift[]
     */
    public function getGifts(): Collection
    {
        return $this->gifts;
    }
}
