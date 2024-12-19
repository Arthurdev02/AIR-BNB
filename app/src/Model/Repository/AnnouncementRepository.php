<?php

namespace App\Model\Repository;

use PDO;
use Symplefony\Database;
use Symplefony\Model\Repository;
use App\Model\Entity\Announcement;

class AnnouncementRepository extends Repository
{
    protected function getTableName(): string
    {
        return 'announcement';
    }

    //creation du crud

    public function createAnnouncement(Announcement $announcement): ?Announcement
    {
        $query = sprintf(
            "INSERT INTO %s (owner_id, adress_id, size, price, description, sleeping)
        VALUES (:owner_id, :adress_id, :size, :price, :description, :sleeping)",
            $this->getTableName()
        );
        $sth = $this->pdo->prepare($query);
        //si la preparation echoue
        if (!$sth) {
            return null;
        }
        $success = $sth->execute([
            'owner_id' => $announcement->getIdOwner(),
            'adress_id' => $announcement->getIdAdress(),
            'size' => $announcement->getSize(),
            'price' => $announcement->getPrice(),
            'description' => $announcement->getDescription(),
            'sleeping' => $announcement->getSleeping(),
        ]);
        //si echec de l'insertion

        if (!$success) {
            return null;
        }

        //ajout de l'id de l'item cree en base de donnees
        $announcement->setId($this->pdo->lastInsertId());
        return $announcement;
    }

    //cRud: read tous les items
    public function getAll(): array
    {
        $announcements = $this->readAll(Announcement::class);
        return $announcements;
    }

    /* cRud: Read tous les items */
    public function getAllForOwner(int $id): array
    {
        $query = sprintf(
            'SELECT * FROM `%s` WHERE owner_id=:owner_id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        // Si la préparation échoue
        if (! $sth) {
            return [];
        }

        $success = $sth->execute(['owner_id' => $id]);

        // Si echec
        if (! $success) {
            return [];
        }

        // Récupération des résultats
        $announcements = [];

        while ($announcement_data = $sth->fetch()) {
            $announcement = new Announcement($announcement_data);
            $announcements[] = $announcement;
        }

        return $announcements;
    }

    //cRud: read un item par son id
    public function getById(int $id): ?Announcement
    {
        return $this->readById(Announcement::class, $id);
    }

    //crUd: update
    public function update(Announcement $announcement): ?Announcement
    {
        $query = sprintf(
            'UPDATE `%s`
                SET
                    `owner_id`=:owner_id,
                    `adress_id`=:adress_id,
                    `size`=:size,
                    `price`=:price,
                    `description`=:description,
                    `sleeping`=:sleeping,
                    `accommodation_id`=:accommodation_id
                WHERE id=:id',
            $this->getTableName()
        );
        $sth = $this->pdo->prepare($query);
        // Si la préparation échoue
        if (! $sth) {
            return null;
        }
        $success = $sth->execute([
            'owner_id' => $announcement->getIdOwner(),
            'adress_id' => $announcement->getIdAdress(),
            'size' => $announcement->getSize(),
            'price' => $announcement->getPrice(),
            'description' => $announcement->getDescription(),
            'sleeping' => $announcement->getSleeping(),
            'accommodation_id' => $announcement->getAccommodationId(),
            'id' => $announcement->getId(),
            'title' => $announcement->getTitle()
        ]);
        // Si echec de la mise à jour
        if (! $success) {
            return null;
        }
        return $announcement;
    }
    public function findById(int $id): ?Announcement
    {
        $query = 'SELECT * FROM announcements WHERE id = :id';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $announcement = new Announcement();
            $announcement->setId($result['id']);
            $announcement->setTitle($result['title']);
            $announcement->setDescription($result['description']);
            $announcement->setSize($result['size']);
            $announcement->setSleeping($result['sleeping']);
            $announcement->setPrice($result['price']);
            $announcement->setIdAdress($result['adress_id']);
            $announcement->setAccommodationId($result['accommodation_id']);
            return $announcement;
        }

        return null; // Retourne null si l'annonce n'existe pas
    }
}
