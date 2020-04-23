<?php

declare(strict_types=1);

namespace App\Users;

use App\Gifts\Gift;

class GiftedUser extends User
{
    /** @var Gift[] */
    private array $gifts;

    public function __construct(User $user, array $gifts)
    {
        parent::__construct($user->getName(), $user->getIconId(), $user->getId());
        $this->gifts = $gifts;
    }

    /**
     * @return Gift[]
     */
    public function getGifts(): array
    {
        return $this->gifts;
    }
}
