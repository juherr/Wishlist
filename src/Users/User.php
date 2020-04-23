<?php

declare(strict_types=1);

namespace App\Users;

class User
{
    private ?int $id;
    private string $name;
    private int $iconId;

    public function __construct(string $name, int $iconId, ?int $id = null)
    {
        if ($id !== null && $id <= 0) {
            throw new \InvalidArgumentException('Invalid id: ' . $id);
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
