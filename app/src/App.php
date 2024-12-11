<?php

/**
 * Classe de démarrage de l'application
 */

// Déclaration du namespace de ce fichier
namespace App;

use Exception;
use Throwable;

use Symplefony\View;
use App\Controller\maisonController;

use MiladRahimi\PhpRouter\Router;
use App\Controller\PageController;
use App\Controller\UserController;
use App\Controller\ownerController;
use App\Middleware\ownerMiddleware;
use App\Controller\LogementController;
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
        $this->router->get('/', [PageController::class, 'index']);
        $this->router->get('/create-account', [PageController::class, 'register']);
        $this->router->get('/connect', [PageController::class, 'connect']);
        $this->router->get('/mentions-legales', [PageController::class, 'legalNotice']);


        // TODO: Groupe Visiteurs (non-connectés)

        // -- Pages d'owner --
        $ownerAttributes = [
            Attributes::PREFIX => '/owner',
            Attributes::MIDDLEWARE => [ownerMiddleware::class]
        ];

        $this->router->group($ownerAttributes, function (Router $router) {
            $router->get('', [ownerController::class, 'dashboard']);

            // -- User --
            // Ajout
            $router->get('/users/add', [UserController::class, 'add']);
            $router->post('/users', [UserController::class, 'create']);
            // Liste
            $router->get('/users', [UserController::class, 'index']);
            // Détail
            $router->get('/users/{id}', [UserController::class, 'show']);
            $router->post('/users/{id}', [UserController::class, 'update']);
            // Suppression
            $router->get('/users/{id}/delete', [UserController::class, 'delete']);

            // -- Logement --
            // Ajout
            $router->get('/logements/add', [LogementController::class, 'add']);
            $router->post('/logements', [LogementController::class, 'create']);
            // Liste
            $router->get('/logements', [LogementController::class, 'index']);
            // Détail/modification
            $router->get('/logements/{id}', [LogementController::class, 'show']);
            $router->post('/logements/{id}', [LogementController::class, 'update']);
            // Suppression
            $router->get('/logements/{id}/delete', [LogementController::class, 'delete']);

            // -- maison --
            // Ajout
            $router->get('/maisons/add', [maisonController::class, 'add']);
            $router->post('/maisons', [maisonController::class, 'create']);
            // Liste
            $router->get('/maisons', [maisonController::class, 'index']);
            // Détail/modification
            $router->get('/maisons/{id}', [maisonController::class, 'show']);
            $router->post('/maisons/{id}', [maisonController::class, 'update']);
            // Suppression
            $router->get('/maisons/{id}/delete', [maisonController::class, 'delete']);
        });
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
            View::renderError(500);
            var_dump($e);
        }
    }

    private function __clone() {}
    public function __wakeup()
    {
        throw new Exception("Non c'est interdit !");
    }
}
