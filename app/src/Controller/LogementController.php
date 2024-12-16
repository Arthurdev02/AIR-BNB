<?php

namespace App\Controller;

use Laminas\Diactoros\ServerRequest;

use Symplefony\Controller;
use Symplefony\View;

use App\Model\Entity\Logements;
use App\Model\Repository\RepoManager;

class LogementsController extends Controller
{
    /**
     * Pages Administrateur
     */

    // Admin: Affichage du formulaire de création d'un utilisateur
    public function add(): void
    {
        $view = new View( 'logement:admin:create', auth_controller: AuthController::class );

        $data = [
            'title' => 'Ajouter une voiture',
            'equipements' => RepoManager::getRM()->getEquipementRepo()->getAll()
        ];

        $view->render( $data );
    }

    // Admin: Traitement du formulaire de création d'une catégorie
    public function create( ServerRequest $request ): void
    {
        $logement_data = $request->getParsedBody();

        $logement = new Logement( $logement_data );
        
        $logement_created = RepoManager::getRM()->getLogementRepo()->create( $logement );
        
        if( is_null( $logement_created ) ) {
            // TODO: gérer une erreur
            $this->redirect( '/admin/logements/add' );
        }

        // Si pas de données post logement aucun coché, on crée un tableau vide
        $equipements =  $logement_data[ 'equipements' ] ?? [];
        $logement_created->addequipements( $equipements );

        $this->redirect( '/admin/logements' );
    }

    // Admin: Liste
    public function index(): void
    {
        $view = new View( 'logement:admin:list', auth_controller: AuthController::class );

        $data = [
            'title' => 'Liste des voitures',
            'logements' => RepoManager::getRM()->getLogementRepo()->getAll()
        ];

        $view->render( $data );
    }

    // Admin: Affichage détail/modification
    public function show( int $id ): void
    {
        $view = new View( 'logement:admin:details', auth_controller: AuthController::class );

        $logement = RepoManager::getRM()->getLogementRepo()->getById( $id );

        // Si l'utilisateur demandé n'existe pas
        if( is_null( $logement ) ) {
            View::renderError( 404, AuthController::class );
            return;
        }

        $logement_equipements_ids = array_map( function( $equipement ){ return $equipement->getId(); }, $logement->getEquipements() );
        $data = [
            'title' => 'Voiture: '. $logement->getLabel(),
            'logement' => $logement,
            'equipements' => RepoManager::getRM()->getEquipementyRepo()->getAll(),
            'logement_equipements_ids' => $logement_equipements_ids
        ];

        $view->render( $data );
    }

    // Admin: Traitement du formulaire de modification
    public function update( ServerRequest $request, int $id ): void
    {
        $logement_data = $request->getParsedBody();

        $logement = new Logement( $logement_data );
        $logement->setId( $id );

        $logement_updated = RepoManager::getRM()->getLogementRepo()->update( $logement );

        if( is_null( $logement_updated ) ) {
            // TODO: gérer une erreur
            $this->redirect( '/admin/logements/'. $id );
        }
        
        // Si pas de données post logement aucun coché, on crée un tableau vide
        $equipements =  $logement_data[ 'equipements' ] ?? [];
        $logement_updated->addEquipements( $equipements );

        $this->redirect( '/admin/logements' );
    }

    // Admin: Suppression
    public function delete( int $id ): void
    {
        $delete_success = RepoManager::getRM()->getLogementRepo()->deleteOne( $id );

        if( ! $delete_success ) {
            // TODO: gérer une erreur
            $this->redirect( '/admin/logements/'. $id );
        }

        $this->redirect( '/admin/logements' );
    }
}