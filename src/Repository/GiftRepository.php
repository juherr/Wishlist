<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Gift;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GiftRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gift::class);
    }

    public function create(Gift $gift): int
    {
        $this->getEntityManager()->persist($gift);
        $this->getEntityManager()->flush();

        return $gift->getId();
    }

    public function findById(int $id): ?Gift
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('Invalid id: ' . $id);
        }
        /** @var Gift|null $gift */
        $gift = $this->find($id);
        return $gift;
    }

    /**
     * @param int $userId
     * @return Gift[]
     */
    public function findByUserId(int $userId)
    {
        if ($userId <= 0) {
            throw new \InvalidArgumentException('Invalid user_id: ' . $userId);
        }
        return $this->findBy(['userId' => $userId]);
    }

    public function update(Gift $gift): void
    {
        $this->getEntityManager()->persist($gift);
        $this->getEntityManager()->flush();
    }

    // TODO replace id by entity
    public function delete(int $id): void
    {
        $gift = $this->findById($id);
        $this->getEntityManager()->remove($gift);
        $this->getEntityManager()->flush();
    }

    public function deleteAll(int $userId): void
    {
        if ($userId <= 0) {
            throw new \InvalidArgumentException('Invalid user_id: ' . $userId);
        }

        $dql = <<<DQL
            DELETE FROM App\Entity\Gift g
            WHERE g.userId = :user_id
        DQL;
        $this->getEntityManager()->createQuery($dql)
            ->setParameter('user_id', $userId)
            ->execute();
    }
}
