<?php

namespace App\Model\Repository;

use App\Model\Entity\logement;
use Symplefony\Model\Repository;

class logementRepository extends Repository
{
    protected function getTableName(): string { return 'logements'; }

    /* Crud: Create */
    public function create( logement $logement ): ?logement
    {
        $query = sprintf(
            'INSERT INTO `%s` 
                (`label`,`seats`,`energy`,`plate_number`,`price_day`,`price_distance`,`image`) 
                VALUES (:label,:seats,:energy,:plate_number,:price_day,:price_distance,:image)',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare( $query );

        // Si la préparation échoue
        if( ! $sth ) {
            return null;
        }

        $success = $sth->execute([
            'label' => $logement->getLabel(),
            'seats' => $logement->getSeats(),
            'energy' => $logement->getEnergy(),
            'plate_number' => $logement->getPlateNumber(),
            'price_day' => $logement->getPriceDay(),
            'price_distance' => $logement->getPriceDistance(),
            'image' => $logement->getImage()
        ]);

        // Si echec de l'insertion
        if( ! $success ) {
            return null;
        }

        // Ajout de l'id de l'item créé en base de données
        $logement->setId( $this->pdo->lastInsertId() );

        return $logement;
    }

    /* cRud: Read tous les items */
    public function getAll(): array
    {
        return $this->readAll( logement::class );
    }

    /* cRud: Read un item par son id */
    public function getById( int $id ): ?logement
    {
        return $this->readById( logement::class, $id );
    }

    /* crUd: Update */
    public function update( logement $logement ): ?logement
    {
        $query = sprintf(
            'UPDATE `%s` 
                SET
                    `label`=:label,
                    `seats`=:seats,
                    `energy`=:energy,
                    `plate_number`=:plate_number,
                    `price_day`=:price_day,
                    `price_distance`=:price_distance,
                    `image`=:image
                WHERE id=:id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare( $query );

        // Si la préparation échoue
        if( ! $sth ) {
            return null;
        }

        $success = $sth->execute([
            'label' => $logement->getLabel(),
            'seats' => $logement->getSeats(),
            'energy' => $logement->getEnergy(),
            'plate_number' => $logement->getPlateNumber(),
            'price_day' => $logement->getPriceDay(),
            'price_distance' => $logement->getPriceDistance(),
            'image' => $logement->getImage(),
            'id' => $logement->getId()
        ]);

        // Si echec de la mise à jour
        if( ! $success ) {
            return null;
        }

        return $logement;
    }

    /* cruD: Delete */
    public function deleteOne(int $id): bool
    {
        // On supprime d'abord toutes les liaisons avec les catégories
        $success = RepoManager::getRM()->getCategoryRepo()->detachAllForlogement( $id );

        // Si cela a fonctionné on invoke la méthode deleteOne parente
        if( $success) {
            $success = parent::deleteOne( $id );
        }

        return $success;
    }
}