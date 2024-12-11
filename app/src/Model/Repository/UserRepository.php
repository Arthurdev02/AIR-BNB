<?php

namespace App\Model\Repository;

use Symplefony\Model\Repository;

use App\Model\Entity\User;

class UserRepository extends Repository
{
    protected function getTableName(): string
    {
        return 'user';
    }

    /* Crud: Create */
    public function create(User $user): ?User
    {
        $query = sprintf(
            'INSERT INTO `%s` 
                (`password`,`email`,`firstname`,`lastname`,`phone_number`,`role_id`) 
                VALUES (:password,:email,:firstname,:lastname,:phone_number,:role_id)',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        // Si la préparation échoue
        if (! $sth) {
            return null;
        }

        $success = $sth->execute([
            'password' => $user->getPassword(),
            'email' => $user->getEmail(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'phone_number' => $user->getPhoneNumber(),
            'role_id' => $user->getRoleId()
        ]);

        // Si echec de l'insertion
        if (! $success) {
            return null;
        }

        // Ajout de l'id de l'item créé en base de données
        $user->setId($this->pdo->lastInsertId());

        return $user;
    }

    /* cRud: Read tous les items */
    public function getAll(): array
    {
        return $this->readAll(User::class);
    }

    /* cRud: Read un item par son id */
    public function getById(int $id): ?User
    {
        return $this->readById(User::class, $id);
    }

    /* crUd: Update */
    public function update(User $user): ?User
    {
        $query = sprintf(
            'UPDATE `%s` 
                SET
                    `password`=:password,
                    `email`=:email,
                    `firstname`=:firstname,
                    `lastname`=:lastname,
                    `phone_number`=:phone_number
                    
                WHERE id=:id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        // Si la préparation échoue
        if (! $sth) {
            return null;
        }

        $success = $sth->execute([
            'password' => $user->getPassword(),
            'email' => $user->getEmail(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'phone_number' => $user->getPhoneNumber(),
            'id' => $user->getId(),
            `role_id` => $user->getRoleId()
        ]);

        // Si echec de la mise à jour
        if (! $success) {
            return null;
        }

        return $user;
    }
}
