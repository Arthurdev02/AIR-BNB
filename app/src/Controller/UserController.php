<?php

namespace App\Controller;

use Laminas\Diactoros\ServerRequest;

use Symplefony\Controller;
use Symplefony\View;

use App\Model\Entity\role;
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
        $view = new View('user:create-account');

        $data = [
            'title' => 'Créer mon compte - Autodingo.com'
        ];

        $view->render($data);
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
        $view = new View('user:owner:create');

        $data = [
            'title' => 'Ajouter un utilisateur'
        ];

        $view->render($data);
    }

    // owner: Traitement du formulaire de création d'un utilisateur
    public function create(ServerRequest $request): void
    {
        $user_data = $request->getParsedBody();
        //var_dump($user_data);
        //die;
        $user = new User($user_data);
        $user_created = RepoManager::getRM()->getUserRepo()->create($user);

        if (is_null($user_created)) {
            // TODO: gérer une erreur
            $this->redirect('/owner/users/add');
        }


        $this->redirect('/users');
    }

    // owner: Liste
    public function index(): void
    {
        $view = new View('page:owner:home');

        $data = [
            'title' => 'Liste des utilisateurs',
            'users' => RepoManager::getRM()->getUserRepo()->getAll()
        ];

        $view->render($data);
    }

    // owner: Détail
    public function show(int $id): void
    {
        $view = new View('user:owner:details');

        $user = RepoManager::getRM()->getUserRepo()->getById($id);

        // Si l'utilisateur demandé n'existe pas
        if (is_null($user)) {
            View::renderError(404);
            return;
        }

        $data = [
            'title' => 'Utilisateur: ' . $user->getEmail(),
            'user' => $user
        ];

        $view->render($data);
    }
}
