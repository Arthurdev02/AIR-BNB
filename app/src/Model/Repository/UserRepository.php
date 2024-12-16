<?php

namespace App\Model\Repository;

use App\App;

use App\Model\Entity\User;
use Symplefony\Model\Repository;

class UserRepository extends Repository
{
    protected function getTableName(): string
    {
        return 'user';
    }

    public function checkuser(string $email, string $password)
    {
        $query = sprintf(
            'SELECT * FROM `%s` WHERE email=:email',
            $this->getTableName()
        );
        $sth = $this->pdo->prepare($query);

        // Si la préparation échoue
        if (! $sth) {
            return null;
        }

        $success = $sth->execute(['email' => $email]);

        // Si echec
        if (! $success) {
            return null;
        }

        // Récupération du premier résultat
        $object_data = $sth->fetch();

        // Si aucun user ne correspond
        if (! $object_data) {
            return null;
        }

        return new User($object_data);
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
        $password = App::strHash($user->getPassword());
        $success = $sth->execute([
            'password' => $password,
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
    public function checkAuth(string $email, string $password): ?User
    {
        $query = sprintf(
            'SELECT * FROM `%s` WHERE `email`=:email AND `password`=:password',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if (! $sth) {
            return null;
        }

        $sth->execute(['email' => $email, 'password' => $password]);

        $user_data = $sth->fetch();

        if (! $user_data) {
            return null;
        }

        return new User($user_data);
    }
}
