<?php

namespace App\Controller;

use App\App;

use App\Session;

use Symplefony\View;
use App\Model\Entity\role;
use App\Model\Entity\User;
use Symplefony\Controller;
use Laminas\Diactoros\ServerRequest;
use App\Model\Repository\RepoManager;

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
    public function homeuser()
    {
        $view = new View('page:user:home');

        $data = [
            'title' => 'Acceuil utilisateur'
        ];

        $view->render($data);
    }
    public function homeowner()
    {
        $view = new View('page:owner:home');

        $data = [
            'title' => 'Acceuil owner'
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
            $this->redirect('/registration');
            $redirect_url = match ($user->getRoleId()) {
                User::ROLE_USER => 'user/home',
                User::ROLE_OWNER => 'owner/home',
            };



            $this->redirect($redirect_url);
        }
    }
    public function login(ServerRequest $request)
    {
        $user_data = $request->getParsedBody();
        //var_dump($user_data);
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


        $data = [
            'title' => 'Utilisateur: ' . $user->getEmail(),
            'user' => $user
        ];

        $view->render($data);
    }
    public function userLogin(ServerRequest $request): void
    {
        $form_data = $request->getParsedBody();


        // Si les données du formulaire sont vides ou inexistantes
        if (empty($form_data['email']) || empty($form_data['password'])) {

            $this->redirect('/connect');
        }

        // On nettoie les espaces en trop
        $email = trim($form_data['email']);
        $password = trim($form_data['password']);


        $password = App::strHash($form_data['password']);
        // On vérifie les identifiants de connexion
        $user = RepoManager::getRM()->getUserRepo()->checkAuth($email, $password);

        // Si échec
        if (is_null($user)) {
            $this->redirect('/connect');
        }

        // On enregistre l'utilisateur correspondant dans la session
        Session::set(Session::USER, $user);


        if ($user->getRoleId() == User::ROLE_USER) {
            $this->redirect('/user/home');
        }
        if ($user->getRoleId() == User::ROLE_OWNER) {
            $this->redirect('/owner/home');
        }
    }
}
