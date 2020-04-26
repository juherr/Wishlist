<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function create(User $user): int
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return $user->getId();
    }

    public function findById(int $id): ?User
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('Invalid id: ' . $id);
        }

        /** @var User|null $user */
        $user = $this->find($id);
        return $user;
    }

    /**
     * @return User[]
     */
    public function findAll(): array
    {
        return parent::findBy([], ['name' => 'ASC']);
    }

    public function update(User $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function delete(User $user): void
    {
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }
}