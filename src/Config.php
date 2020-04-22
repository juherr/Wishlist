<?php

declare(strict_types=1);

namespace Wishlist;

final class Config
{
    private function __construct()
    {
    }

    public static function getUserTableName(): string
    {
        return 'personne';
    }

    public static function getGiftTableName(): string
    {
        return 'liste';
    }
}
