<?php

declare(strict_types=1);

namespace App\Users;

use App\Repository\GiftRepository;
use App\Repository\UserRepository;

class UserService
{
    private UserRepository $repository;
    private GiftRepository $giftRepository;

    public function __construct(UserRepository $repository, GiftRepository $giftRepository)
    {
        $this->repository = $repository;
        $this->giftRepository = $giftRepository;
    }

    /**
     * @return GiftedUser[]
     */
    public function findAll(): array
    {
        $users = $this->repository->findAll();
        $giftedUsers = [];
        foreach ($users as $user) {
            $gifts = $this->giftRepository->findByUserId($user->getId());
            $giftedUsers[] = new GiftedUser($user, $gifts);
        }

        return $giftedUsers;
    }

    public function delete(int $id): void
    {
        $this->repository->delete($id);
        // TODO remove, should be done by cascading
        $this->giftRepository->deleteAll($id);
    }
}
