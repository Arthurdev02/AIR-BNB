<?php

/**
 * Classe de démarrage de l'application
 */

// Déclaration du namespace de ce fichier
namespace App;

use Exception;
use Throwable;

use Symplefony\View;
use App\Controller\logementController;

use MiladRahimi\PhpRouter\Router;
use App\Controller\PageController;
use App\Controller\UserController;
use App\Controller\ownerController;
use App\Middleware\ownerMiddleware;
use App\Controller\CategoryController;
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

            // -- Category --
            // Ajout
            $router->get('/categories/add', [CategoryController::class, 'add']);
            $router->post('/categories', [CategoryController::class, 'create']);
            // Liste
            $router->get('/categories', [CategoryController::class, 'index']);
            // Détail/modification
            $router->get('/categories/{id}', [CategoryController::class, 'show']);
            $router->post('/categories/{id}', [CategoryController::class, 'update']);
            // Suppression
            $router->get('/categories/{id}/delete', [CategoryController::class, 'delete']);

            // -- logement --
            // Ajout
            $router->get('/logements/add', [logementController::class, 'add']);
            $router->post('/logements', [logementController::class, 'create']);
            // Liste
            $router->get('/logements', [logementController::class, 'index']);
            // Détail/modification
            $router->get('/logements/{id}', [logementController::class, 'show']);
            $router->post('/logements/{id}', [logementController::class, 'update']);
            // Suppression
            $router->get('/logements/{id}/delete', [logementController::class, 'delete']);
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
