<?php

namespace App\Controller;

use Laminas\Diactoros\ServerRequest;

use Symplefony\Controller;
use Symplefony\View;

use App\Model\Entity\logement;
use App\Model\Repository\logementRepository;
use App\Model\Repository\RepoManager;

class logementController extends Controller
{
    /**
     * Pages owneristrateur
     */

    // owner: Affichage du formulaire de création d'un utilisateur
    public function add(): void
    {
        $view = new View( 'logement:owner:create' );

        $data = [
            'title' => 'Ajouter un logement'
        ];

        $view->render( $data );
    }

    // owner: Traitement du formulaire de création d'un logement
    public function create( ServerRequest $request ): void
    {
        $logement_data = $request->getParsedBody();

        $logement = new logement( $logement_data );

        $logement_created = RepoManager::getRM()->getlogementRepo()->create( $logement );

        if( is_null( $logement_created ) ) {
            // TODO: gérer une erreur
            $this->redirect( '/owner/logements/add' );
        }

        $this->redirect( '/owner/logements' );
    }

    // owner: Liste
    public function index(): void
    {
        $view = new View( 'logement:owner:list' );

        $data = [
            'title' => 'Liste des logements',
            'logements' => RepoManager::getRM()->getlogementRepo()->getAll()
        ];

        $view->render( $data );
    }

    // owner: Affichage détail/modification
    public function show( int $id ): void
    {
        $view = new View( 'logement:owner:details' );

        $logement = RepoManager::getRM()->getlogementRepo()->getById( $id );

        // Si l'utilisateur demandé n'existe pas
        if( is_null( $logement ) ) {
            View::renderError( 404 );
            return;
        }

        $data = [
            'title' => 'Categorie: '. $logement->getLabel(),
            'logement' => $logement
        ];

        $view->render( $data );
    }

    // owner: Traitement du formulaire de modification
    public function update( ServerRequest $request, int $id ): void
    {
        $logement_data = $request->getParsedBody();

        $logement = new logement( $logement_data );
        $logement->setId( $id );

        $logement_updated = RepoManager::getRM()->getlogementRepo()->update( $logement );

        if( is_null( $logement_updated ) ) {
            // TODO: gérer une erreur
            $this->redirect( '/owner/logements/'. $id );
        }

        $this->redirect( '/owner/logements' );
    }

    // owner: Suppression
    public function delete( int $id ): void
    {
        $delete_success = RepoManager::getRM()->getlogementRepo()->deleteOne( $id );

        if( ! $delete_success ) {
            // TODO: gérer une erreur
            $this->redirect( '/owner/logements/'. $id );
        }

        $this->redirect( '/owner/logements' );
    }
}