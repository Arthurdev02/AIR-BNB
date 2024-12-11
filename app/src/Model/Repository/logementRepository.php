<?php

namespace App\Model\Repository;

use App\Model\Entity\logement;
use Symplefony\Model\Repository;

class logementRepository extends Repository
{
    protected function getTableName(): string { return 'logemetns'; }
    private function getMappingMaison(): string { return 'Maison_logement'; }

    /* Crud: Create */
    public function create( logement $logement ): ?logement
    {
        $query = sprintf(
            'INSERT INTO `%s` 
                (`label`) 
                VALUES (:label)',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare( $query );

        // Si la préparation échoue
        if( ! $sth ) {
            return null;
        }

        $success = $sth->execute([
            'label' => $logement->getLabel()
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

    /* cRud: Read avec liaison de tous les items reliés à une voiture donnée */
    public function getAllForMaison( int $id ): array
    {
        $query = sprintf(
            'SELECT c.* FROM `%1$s` as c 
                JOIN `%2$s` as cc ON cc.logement_id = c.id
                WHERE cc.Maison_id=:id',
            $this->getTableName(),
            $this->getMappingMaison()
        );

        $sth = $this->pdo->prepare( $query );

        // Si la préparation échoue
        if( ! $sth ) {
            return [];
        }

        $success = $sth->execute([
            'id' => $id
        ]);

        // Si echec de l'insertion
        if( ! $success ) {
            return [];
        }

        $logemetns = [];

        while( $logement_data = $sth->fetch() ) {
            $logemetns[] = new logement( $logement_data );
        }

        return $logemetns;
    }

    /* crUd: Update */
    public function update( logement $logement ): ?logement
    {
        $query = sprintf(
            'UPDATE `%s` 
                SET `label`=:label
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
            'id' => $logement->getId()
        ]);

        // Si echec de la mise à jour
        if( ! $success ) {
            return null;
        }

        return $logement;
    }

    /* Delete toutes les liaisons de catégories d'une voiture donnée */
    public function detachAllForMaison( int $id ): bool
    {
        $query = sprintf(
            'DELETE FROM `%s` WHERE Maison_id=:id',
            $this->getMappingMaison()
        );

        $sth = $this->pdo->prepare( $query );

        // Si la préparation échoue
        if( ! $sth ) {
            return false;
        }

        $success = $sth->execute([ 'id' => $id ]);

        return $success;
    }

    /* Insére les liaisons de catégories demandée pour d'une voiture donnée */
    public function attachForMaison( array $ids_logemetns, int $Maison_id ): bool
    {
        $query_values = [];
        foreach( $ids_logemetns as $logement_id ) {
            $query_values[] = sprintf( '( %s,%s )', $logement_id, $Maison_id );
        }

        $query = sprintf(
            'INSERT INTO `%s` 
                (`logement_id`, `Maison_id`) 
                VALUES %s',
            $this->getMappingMaison(),
            implode( ',', $query_values )
        );

        $sth = $this->pdo->prepare( $query );

        // Si la préparation échoue
        if( ! $sth ) {
            return false;
        }

        return $sth->execute();
    }
}