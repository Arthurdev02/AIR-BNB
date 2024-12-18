<?php

namespace App\Controller;

use PDO;

use Symplefony\Controller;
use Symplefony\Database;
use Symplefony\View;

use App\Model\UserModel;

class PageController extends Controller
{
    // Page d'accueil
    public function index(): void
    {
        $view = new View('page:home');

        $data = [
            'title' => 'Accueil - Air-bnb.com'
        ];

        $view->render($data);
    }

    public function register(): void
    {
        $view = new View('page:user:create-account');

        $data = [
            'title' => 'Créer son compte - Air-bnb.com'
        ];

        $view->render($data);
    }

    public function connect(): void
    {
        $view = new View('page:user:connect');

        $data = [
            'title' => 'Créer son compte - Air-bnb.com'
        ];

        $view->render($data);
    }


    // Page mentions légales
    public function legalNotice(): void
    {
        echo 'Les mentions légales !';
    }


    public function home()
    {
        $view = new View('page:user:connect');

        $data = [
            'title' => 'Home'
        ];

        $view->render($data);
    }
    public function createannounce(): void
    {
        $view = new View('page:owner:createannounce');

        $data = [
            'title' => 'CAZKEN.com - owner '
        ];

        $view->render($data);
    }

    public function oldannounce(): void
    {
        $view = new View('page:owner:oldannounce');

        $data = [
            'title' => 'CAZKEN.com - owner '
        ];

        $view->render($data);
    }
    public function manageannounce(): void
    {
        $view = new View('page:owner:manageannounce');

        $data = [
            'title' => 'CAZKEN.com - owner '
        ];

        $view->render($data);
    }
}
