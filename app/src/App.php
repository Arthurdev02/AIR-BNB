<?php

/**
 * Classe de démarrage de l'application
 */

// Déclaration du namespace de ce fichier
namespace App;

use App\Controller\AnnonceController;
use Exception;
use Throwable;

use Symplefony\View;
use Symplefony\Security;

use MiladRahimi\PhpRouter\Router;
use App\Controller\PageController;
use App\Controller\UserController;
use App\Controller\ownerController;
use App\Middleware\ownerMiddleware;
use App\Controller\maisonController;
use App\Controller\LogementController;
use App\Model\Entity\User;
use MiladRahimi\PhpRouter\Routing\Attributes;
use MiladRahimi\PhpRouter\Exceptions\RouteNotFoundException;

final class App
{
    private static ?self $app_instance = null;

    // Le routeur de l'application
    private Router $router;
    public function getRouter(): Router
    {
        return $this->router;
    }

    public static function getApp(): self
    {
        // Si l'instance n'existe pas encore on la crée
        if (is_null(self::$app_instance)) {
            self::$app_instance = new self();
        }

        return self::$app_instance;
    }

    // Démarrage de l'application
    public function start(): void
    {
        session_start();
        $this->registerRoutes();
        $this->startRouter();
    }

    private function __construct()
    {
        // Création du routeur
        $this->router = Router::create();
    }

    // Enregistrement des routes de l'application
    private function registerRoutes(): void
    {
        // -- Formats des paramètres --
        // {id} doit être un nombre
        $this->router->pattern('id', '\d+');

        // -- Pages communes --
        $this->router->get('/home', [PageController::class, 'index']);
        $this->router->get('/create-account', [PageController::class, 'register']);
        $this->router->get('/connect', [PageController::class, 'connect']);
        $this->router->post('/connect', [UserController::class, 'login']);
        $this->router->get('/mentions-legales', [PageController::class, 'legalNotice']);
        $this->router->get('/owner/dashboard', [PageController::class, 'dashboard']);
        $this->router->post('/users', [UserController::class, 'create']);
        $this->router->get('/user/home', [UserController::class, 'homeuser']);
        $this->router->get('/owner/home', [UserController::class, 'homeowner']);
        $this->router->get('/owner/createannounce', [PageController::class, 'createannounce']);
        $this->router->get('/owner/oldannounce', [PageController::class, 'oldannounce']);
        $this->router->get('/owner/manageannounce', [PageController::class, 'manageannounce']);
        $this->router->post('/owner/createannounce', [AnnonceController::class, 'createAnnouncement']);





        // TODO: Groupe Visiteurs (non-connectés)

        // -- Pages d'owner --
        $ownerAttributes = [
            Attributes::PREFIX => '/owner',
            Attributes::MIDDLEWARE => [ownerMiddleware::class]
        ];
        //--




        // -- User --
        // Ajout
        $this->router->get('/users/add', [UserController::class, 'add']);
        // Liste
        $this->router->get('/users', [UserController::class, 'index']);
        // Détail
        $this->router->get('/users/{id}', [UserController::class, 'show']);
        $this->router->post('/users/{id}', [UserController::class, 'update']);
        // Suppression
        $this->router->get('/users/{id}/delete', [UserController::class, 'delete']);

        // -- Logement --
        // Ajout
        $this->router->get('/logements/add', [LogementController::class, 'add']);
        $this->router->post('/logements', [LogementController::class, 'create']);
        // Liste
        $this->router->get('/logements', [LogementController::class, 'index']);
        // Détail/modification
        $this->router->get('/logements/{id}', [LogementController::class, 'show']);
        $this->router->post('/logements/{id}', [LogementController::class, 'update']);
        // Suppression
        $this->router->get('/logements/{id}/delete', [LogementController::class, 'delete']);
    }

    // Démarrage du routeur
    private function startRouter(): void
    {
        try {
            $this->router->dispatch();
        }
        // Page 404 avec status HTTP adequat pour les pages non listée dans le routeur
        catch (RouteNotFoundException $e) {
            View::renderError(404);
        }
        // Erreur 500 pour tout autre problème temporaire ou non
        catch (Throwable $e) {
            View::renderError(500, $e);
            //var_dump($e);
        }
    }

    private function __clone() {}
    public function __wakeup()
    {
        throw new Exception("Non c'est interdit !");
    }
    /**
     * Hache une chaîne de caractères en servant du "sel" et du "poivre" définis dans .env
     *
     * @param  string $str Chaîne à hacher
     * 
     * @return string Résultat
     */
    public static function strHash(string $str): string
    {
        return Security::strButcher($str, $_ENV['security_salt'], $_ENV['security_pepper']);
    }
}
