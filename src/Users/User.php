<?php

declare(strict_types=1);

namespace Wishlist\Users;

class User
{
    private ?int $id;
    private string $username;
    private int $iconId;

    public function __construct(string $username, int $iconId, ?int $id = null)
    {
        if ($id !== null && $id <= 0) {
            throw new \InvalidArgumentException('Invalid id: ' . $id);
        }
        $this->id = $id;
        $this->username = $username;
        $this->iconId = $iconId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
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
