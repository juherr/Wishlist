<?php

declare(strict_types=1);

namespace Wishlist\Users;

class UserRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(User $user): int
    {
        $statement = $this->pdo->prepare(<<<SQL
            INSERT INTO kdo_personne (id_personne, nom_personne, choix_illu) 
            VALUES (NULL, :username, :icon_id)
        SQL);
        $statement->bindValue(':username', $user->getUsername());
        $statement->bindValue(':icon_id', $user->getIconId());

        $statement->execute();

        return (int) $this->pdo->lastInsertId();
    }

    public function findById(int $id): ?User
    {
        $statement = $this->pdo->prepare(<<<SQL
            SELECT id_personne, nom_personne, choix_illu from kdo_personne 
            WHERE id_personne = :id
        SQL);
        $statement->bindValue(':id', $id);
        $statement->execute();
        $item = $statement->fetch();

        return self::createUser($item);
    }

    /**
     * @return User[]
     */
    public function findAll(): array
    {
        $statement = $this->pdo->prepare(<<<SQL
            SELECT id_personne, nom_personne, choix_illu from kdo_personne 
            ORDER BY nom_personne ASC
        SQL);
        $statement->execute();

        $users = [];
        foreach($statement->fetchAll() as $item) {
            $users[] = self::createUser($item);
        }
        return $users;
    }

    private static function createUser(?array $item): ?User
    {
        if ($item === null) {
            return null;
        }

        return new User(
            $item['nom_personne'],
            (int)$item['choix_illu'],
            (int)$item['id_personne']
        );
    }

    public function update(User $user): void
    {
        $statement = $this->pdo->prepare(<<<SQL
            UPDATE kdo_personne
            SET nom_personne = :username, 
                choix_illu = :icon_id 
            WHERE id_personne = :id
        SQL);

        $statement->bindValue(':username', $user->getUsername());
        $statement->bindValue(':icon_id', $user->getIconId());
        $statement->bindValue(':id', $user->getId());
        $statement->execute();
    }

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare('DELETE FROM kdo_personne WHERE id_personne = :id');
        $statement->bindValue(':id', $id);
        $statement->execute();
    }
}
