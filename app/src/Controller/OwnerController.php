<?php

namespace App\Controller;

use Symplefony\Controller;
use Symplefony\View;

class OwnerController extends Controller
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
}
