<?php

declare(strict_types=1);

namespace Wishlist\Gifts;

class GiftRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(Gift $gift): int
    {
        $statement = $this->pdo->prepare(<<<SQL
            INSERT INTO kdo_liste (id, la_personne, titre, lien, description, iduser_resa, reserve) 
            VALUES (NULL, :user_id, :gift_title, :gift_link, :gift_description, 0, 0)
        SQL);
        $statement->bindValue(':user_id', $gift->getUserId());
        $statement->bindValue(':gift_title', $gift->getTitle());
        $statement->bindValue(':gift_link', $gift->getLink());
        $statement->bindValue(':gift_description', $gift->getDescription());

        $statement->execute();

        return (int) $this->pdo->lastInsertId();
    }

    public function findById(int $id): ?Gift
    {
        $statement = $this->pdo->prepare(<<<SQL
            SELECT id, la_personne, titre, lien, description, iduser_resa, reserve from kdo_liste 
            WHERE id = :id
        SQL);
        $statement->bindValue(':id', $id);
        $statement->execute();
        $item = $statement->fetch(\PDO::FETCH_ASSOC);

        return self::createGift($item);
    }

    /**
     * @param int $userId
     * @return Gift[]
     */
    public function findByUserId(int $userId): array
    {
        $statement = $this->pdo->prepare(<<<SQL
            SELECT id, la_personne, titre, lien, description, iduser_resa, reserve from kdo_liste 
            WHERE la_personne = :user_id
        SQL);
        $statement->bindValue(':user_id', $userId);
        $statement->execute();
        $result = $statement->fetchAll();

        $gifts = [];
        foreach ($result as $item) {
            $gifts[] = self::createGift($item);
        }
        return $gifts;
    }

    public static function createGift(?array $item): ?Gift
    {
        if ($item === null) {
            return null;
        }
        $idUserResa = (int)$item['iduser_resa'];
        if ($idUserResa === 0) {
            $idUserResa = null;
        }
        return new Gift(
            (int)$item['la_personne'],
            $item['titre'],
            $item['link'],
            $item['description'],
            (bool)$item['reserve'],
            $idUserResa,
            (int)$item['id'],
        );
    }

    public function update(Gift $gift): void
    {
        $statement = $this->pdo->prepare(<<<SQL
            UPDATE kdo_liste
            SET titre = :title, 
                lien = :lien, 
                description = :description,
                reserve = :etat, 
                IdUser_resa = :userResa
            WHERE id = :id
        SQL);

        $statement->bindValue(':title', $gift->getTitle());
        $statement->bindValue(':lien', $gift->getLink());
        $statement->bindValue(':description', $gift->getDescription());
        $statement->bindValue(':etat', $gift->isBooked() ? 1 : 0);
        $statement->bindValue(':userResa', $gift->getBookedByUserId() === null ? 0 : $gift->getBookedByUserId());
        $statement->bindValue(':id', $gift->getId());

        $statement->execute();
    }

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare('DELETE FROM kdo_liste WHERE id = :id');
        $statement->bindValue(':id', $id);
        $statement->execute();
    }

    public function deleteAll(int $userId): void
    {
        $statement = $this->pdo->prepare('DELETE FROM liste WHERE la_personne = :user_id');
        $statement->bindValue(':user_id', $userId);
        $statement->execute();
    }
}
