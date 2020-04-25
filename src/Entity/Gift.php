<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="kdo_gifts")
 */
class Gift
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="gifts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private User $user;

    /**
     * @ORM\Column(type="string", name="title", length=300)
     */
    private string $title;

    /**
     * @ORM\Column(type="string", name="link", length=2000, nullable=true)
     */
    private ?string $link;

    /**
     * @ORM\Column(type="text", name="description", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="booked_by", referencedColumnName="id", nullable=true)
     */
    private ?User $bookedBy;

    public function __construct(
        User $user,
        string $title,
        ?string $link,
        ?string $description,
        ?User $bookedBy = null,
        ?int $id = null
    )
    {
        if ($id !== null && $id <= 0) {
            throw new \InvalidArgumentException('Invalid id: ' . $id);
        }

        $this->user = $user;
        $this->title = $title;
        $this->link = $link;
        $this->description = $description;
        $this->bookedBy = $bookedBy;
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): void
    {
        if (empty($link)) {
            $link = null;
        }
        $this->link = $link;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        if (empty($description)) {
            $description = null;
        }
        $this->description = $description;
    }

    public function isBooked(): bool
    {
        return $this->bookedBy !== null && $this->bookedBy->getId() > 0;
    }

    public function getBookedBy(): ?User
    {
        return $this->bookedBy;
    }

    public function book(User $user): void
    {
        if ($this->isBooked()) {
            throw new \InvalidArgumentException('Gift already booked');
        }
        if ($user->getId() <= 0) {
            throw new \InvalidArgumentException('Invalid user');
        }
        $this->bookedBy = $user;
    }

    public function cancelBooking(): void
    {
        if (!$this->isBooked()) {
            throw new \InvalidArgumentException('Gift not booked');
        }
        $this->bookedBy = null;
    }
}
