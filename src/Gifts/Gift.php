<?php

declare(strict_types=1);

namespace App\Gifts;

class Gift
{
    private ?int $id;
    private int $userId;
    private string $title;
    private ?string $link;
    private string $description;
    private bool $isBooked;
    private ?int $bookedByUserId;

    public function __construct(
        int $userId,
        string $title,
        ?string $link,
        string $description,
        bool $isBooked,
        ?int $bookedByUserId = null,
        ?int $id = null
    )
    {
        if ($id !== null && $id <= 0) {
            throw new \InvalidArgumentException('Invalid id: ' . $id);
        }
        if ($userId <= 0) {
            throw new \InvalidArgumentException('Invalid user_id: ' . $userId);
        }
        if ($bookedByUserId !== null && $bookedByUserId <= 0) {
            throw new \InvalidArgumentException('Invalid booked_by_user_id: ' . $bookedByUserId);
        }
        if ($isBooked && $bookedByUserId === null) {
            throw new \InvalidArgumentException('Booked gift but no user');
        }

        $this->userId = $userId;
        $this->title = $title;
        $this->link = $link;
        $this->description = $description;
        $this->isBooked = $isBooked;
        $this->bookedByUserId = $bookedByUserId;
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
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
        $this->link = $link;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function isBooked(): bool
    {
        return $this->isBooked;
    }

    public function getBookedByUserId(): ?int
    {
        return $this->bookedByUserId;
    }

    public function book(int $bookedByUserId): void
    {
        if ($bookedByUserId <= 0) {
            throw new \InvalidArgumentException('Invalid user_id: ' . $bookedByUserId);
        }
        $this->bookedByUserId = $bookedByUserId;
        $this->isBooked = true;
    }

    public function cancelBooking(): void
    {
        $this->bookedByUserId = null;
        $this->isBooked = false;
    }
}
