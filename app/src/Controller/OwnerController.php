<?php

namespace App\Controller;

use Symplefony\Controller;
use Symplefony\View;

class OwnerController extends Controller
{
    // Dashboard
    public function dashboard(): void
    {
        $view = new View( 'page:owner:home' );

        $data = [
            'title' => 'Tableau de bord - owner Autodingo.com'
        ];

        $view->render( $data);
    }
}