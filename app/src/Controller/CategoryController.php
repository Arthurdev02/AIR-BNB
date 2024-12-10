<?php

namespace App\Controller;

use Laminas\Diactoros\ServerRequest;

use Symplefony\Controller;
use Symplefony\View;

use App\Model\Entity\Category;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\RepoManager;

class CategoryController extends Controller
{
    /**
     * Pages owneristrateur
     */

    // owner: Affichage du formulaire de création d'un utilisateur
    public function add(): void
    {
        $view = new View( 'category:owner:create' );

        $data = [
            'title' => 'Ajouter une catégorie'
        ];

        $view->render( $data );
    }

    // owner: Traitement du formulaire de création d'une catégorie
    public function create( ServerRequest $request ): void
    {
        $category_data = $request->getParsedBody();

        $category = new Category( $category_data );

        $category_created = RepoManager::getRM()->getCategoryRepo()->create( $category );

        if( is_null( $category_created ) ) {
            // TODO: gérer une erreur
            $this->redirect( '/owner/categories/add' );
        }

        $this->redirect( '/owner/categories' );
    }

    // owner: Liste
    public function index(): void
    {
        $view = new View( 'category:owner:list' );

        $data = [
            'title' => 'Liste des catégories',
            'categories' => RepoManager::getRM()->getCategoryRepo()->getAll()
        ];

        $view->render( $data );
    }

    // owner: Affichage détail/modification
    public function show( int $id ): void
    {
        $view = new View( 'category:owner:details' );

        $category = RepoManager::getRM()->getCategoryRepo()->getById( $id );

        // Si l'utilisateur demandé n'existe pas
        if( is_null( $category ) ) {
            View::renderError( 404 );
            return;
        }

        $data = [
            'title' => 'Categorie: '. $category->getLabel(),
            'category' => $category
        ];

        $view->render( $data );
    }

    // owner: Traitement du formulaire de modification
    public function update( ServerRequest $request, int $id ): void
    {
        $category_data = $request->getParsedBody();

        $category = new Category( $category_data );
        $category->setId( $id );

        $category_updated = RepoManager::getRM()->getCategoryRepo()->update( $category );

        if( is_null( $category_updated ) ) {
            // TODO: gérer une erreur
            $this->redirect( '/owner/categories/'. $id );
        }

        $this->redirect( '/owner/categories' );
    }

    // owner: Suppression
    public function delete( int $id ): void
    {
        $delete_success = RepoManager::getRM()->getCategoryRepo()->deleteOne( $id );

        if( ! $delete_success ) {
            // TODO: gérer une erreur
            $this->redirect( '/owner/categories/'. $id );
        }

        $this->redirect( '/owner/categories' );
    }
}