<?php

namespace App\Controller;

use Symplefony\Controller;
use Symplefony\View;

class ownerController extends Controller
{
    // AcceuilOwner
    public function AcceuilOwner(): void
    {
        $view = new View('page:owner:home');

        $data = [
            'title' => 'CAZKEN.com - owner '
        ];

        $view->render($data);
    }

    public function dashboard(): void
    {
        $view = new View('page:owner:home');

        $data = [
            'title' => 'CAZKEN.com - owner '
        ];

        $view->render($data);
    }
}
