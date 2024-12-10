<?php

namespace App\Controller;

use Laminas\Diactoros\ServerRequest;

use Symplefony\Controller;
use Symplefony\View;

use App\Model\Entity\Address;
use App\Model\Repository\RepoManager;
use App\Model\Entity\User;

class UserController extends Controller
{
    /**
     * Pages publiques
     */
    // Visiteur: Affichage du formulaire de création de compte
    public function displaySubscribe(): void
    {
        $view = new View( 'user:create-account' );

        $data = [
            'title' => 'Créer mon compte - Autodingo.com'
        ];

        $view->render( $data );
    }

    // Visiteur: Traitement du formulaire de création de compte
    public function processSubscribe(): void
    {
        // TODO: :)
    }

    /**
     * Pages owneristrateur
     */

    // owner: Affichage du formulaire de création d'un utilisateur
    public function add(): void
    {
        $view = new View( 'user:owner:create' );

        $data = [
            'title' => 'Ajouter un utilisateur'
        ];

        $view->render( $data );
    }

    // owner: Traitement du formulaire de création d'un utilisateur
    public function create( ServerRequest $request ): void
    {
        $user_data = $request->getParsedBody();

        $address = new Address( $user_data );

        $address_created = RepoManager::getRM()->getAddressRepo()->create( $address );

        if( is_null( $address_created ) ) {
            // TODO: gérer une erreur
            $this->redirect( '/owner/users/add' );
        }

        $user = new User( $user_data );
        $user->setAddressId( $address_created->getId() );
        $user->setAddress( $address_created );

        $user_created = RepoManager::getRM()->getUserRepo()->create( $user );

        if( is_null( $user_created ) ) {
            // TODO: gérer une erreur
            $this->redirect( '/owner/users/add' );
        }

        $this->redirect( '/owner/users' );
    }

    // owner: Liste
    public function index(): void
    {
        $view = new View( 'user:owner:list' );

        $data = [
            'title' => 'Liste des utilisateurs',
            'users' => RepoManager::getRM()->getUserRepo()->getAll()
        ];

        $view->render( $data );
    }

    // owner: Détail
    public function show( int $id ): void
    {
        $view = new View( 'user:owner:details' );

        $user = RepoManager::getRM()->getUserRepo()->getById( $id );

        // Si l'utilisateur demandé n'existe pas
        if( is_null( $user ) ) {
            View::renderError( 404 );
            return;
        }

        $data = [
            'title' => 'Utilisateur: '. $user->getEmail(),
            'user' => $user
        ];

        $view->render( $data );
    }

    // owner: Traitement du formulaire de modification
    public function update( ServerRequest $request, int $id ): void
    {
        $user_data = $request->getParsedBody();

        $user = new User( $user_data );
        $user->setId( $id );

        $user_updated = RepoManager::getRM()->getUserRepo()->update( $user );

        if( is_null( $user_updated ) ) {
            // TODO: gérer une erreur
            $this->redirect( '/owner/users/'. $id );
        }

        // Update de l'addresse
        $address = new Address( $user_data );
        $address->setId( $user_updated->getAddressId() );

        $address_updated = RepoManager::getRM()->getAddressRepo()->update( $address );

        if( is_null( $address_updated ) ) {
            // TODO: gérer une erreur
            $this->redirect( '/owner/users/'. $id );
        }

        $this->redirect( '/owner/users' );
    }

    // owner: Suppression
    public function delete( int $id ): void
    {
        $delete_success = RepoManager::getRM()->getUserRepo()->deleteOne( $id );

        if( ! $delete_success ) {
            // TODO: gérer une erreur
            $this->redirect( '/owner/users/'. $id );
        }

        $this->redirect( '/owner/users' );
    }
}