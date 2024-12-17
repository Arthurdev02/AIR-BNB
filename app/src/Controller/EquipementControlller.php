<?php

namespace App\Controller;

use Laminas\Diactoros\ServerRequest;

use Symplefony\Controller;
use Symplefony\View;

use App\Model\Entity\Equipement;
use App\Model\Repository\RepoManager;

class EquipementController extends Controller
{
    /**
     * Pages Administrateur
     */

    // Admin: Affichage du formulaire de création d'un utilisateur
    public function add(): void
    {
        $view = new View('equipement:admin:create', auth_controller: AuthController::class);

        $data = [
            'title' => 'Ajouter une catégorie'
        ];

        $view->render($data);
    }

    // Admin: Traitement du formulaire de création d'une catégorie
    public function create(ServerRequest $request): void
    {
        $equipement_data = $request->getParsedBody();

        $equipement = new Equipement($equipement_data);

        $equipement_created = RepoManager::getRM()->getEquipmentRepomentRepo()->create($equipement);

        if (is_null($equipement_created)) {
            // TODO: gérer une erreur
            $this->redirect('/admin/categories/add');
        }

        $this->redirect('/admin/categories');
    }

    // Admin: Liste
    public function index(): void
    {
        $view = new View('equipement:admin:list', auth_controller: AuthController::class);

        $data = [
            'title' => 'Liste des catégories',
            'categories' => RepoManager::getRM()->getEquipementRepo()->getAll()
        ];

        $view->render($data);
    }

    // Admin: Affichage détail/modification
    public function show(int $id): void
    {
        $view = new View('equipement:admin:details', auth_controller: AuthController::class);

        $equipement = RepoManager::getRM()->getEquipementRepo()->getById($id);

        // Si l'utilisateur demandé n'existe pas
        if (is_null($equipement)) {
            View::renderError(404, AuthController::class);
            return;
        }

        $data = [
            'title' => 'Categorie: ' . $equipement->getLabel(),
            'equipement' => $equipement
        ];

        $view->render($data);
    }

    // Admin: Traitement du formulaire de modification
    public function update(ServerRequest $request, int $id): void
    {
        $equipement_data = $request->getParsedBody();

        $equipement = new Equipement($equipement_data);
        $equipement->setId($id);

        $equipement_updated = RepoManager::getRM()->getEquipementRepo()->update($equipement);

        if (is_null($equipement_updated)) {
            // TODO: gérer une erreur
            $this->redirect('/admin/categories/' . $id);
        }

        $this->redirect('/admin/categories');
    }

    // Admin: Suppression
    public function delete(int $id): void
    {
        $delete_success = RepoManager::getRM()->getEquipementRepo()->deleteOne($id);

        if (! $delete_success) {
            // TODO: gérer une erreur
            $this->redirect('/admin/categories/' . $id);
        }

        $this->redirect('/admin/categories');
    }
}
