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
}
