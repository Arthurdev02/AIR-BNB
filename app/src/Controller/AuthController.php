<?php

namespace App\Controller;

use App\App;
use App\Session;
use App\Model\Entity\User;
use Symplefony\Controller;
use Laminas\Diactoros\ServerRequest;
use App\Model\Repository\RepoManager;

class AuthController extends Controller
{
    public static function isOwner(): bool
    {
        // TODO: Le vrai contrôle de session
        return true;
    }
    public function checkCredentials(ServerRequest $request): void
    {
        $form_data = $request->getParsedBody();

        // Si les données du formulaire sont vides ou inexistantes
        if (empty($form_data['email']) || empty($form_data['password'])) {
            // TODO: gérer une erreur
            $this->redirect('/sign-in');
        }

        // On nettoie les espaces en trop
        $email = trim($form_data['email']);
        $password = trim($form_data['password']);

        // Si les données sont vides après nettoyage
        if (empty($email) || empty($password)) {
            // TODO: gérer une erreur
            $this->redirect('/sign-in');
        }

        // Chiffrement du mot de passe
        $password = App::strHash($password);

        // On vérifie les identifiants de connexion
        $user = RepoManager::getRM()->getUserRepo()->checkAuth($email, $password);

        // Si échec
        if (is_null($user)) {
            // TODO: gérer une erreur
            $this->redirect('/sign-in');
        }

        // On enregistre l'utilisateur correspondant dans la session
        Session::set(Session::USER, $user);

        // On redirige vers une page en fonction du rôle de l'utilisateur
        $redirect_url = match ($user->getRoleId()) {
            User::ROLE_CUSTOMER => '/',
            User::ROLE_SALESMAN => '/', // TODO: à définir
            User::ROLE_ADMIN => '/owner' // TODO: Sécurité: page qui redemande le mot de passe par exemple
        };

        $this->redirect($redirect_url);
    }
}
