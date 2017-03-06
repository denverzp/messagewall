<?php

namespace App\Controller;

use App\Engine\Controller;
use App\Engine\Traits\Users;

/**
 * Class HomeController.
 */
class HomeController extends Controller
{
    use Users;

    public function index()
    {
        $this->data['auth'] = $this->isUserAuth();

        //if already auth user - redirect to wall
        if (true === $this->data['auth']) {
            $this->redirect(HTTP_SERVER . '?wall');
        }

        $this->template = 'home';

        $this->render();
    }
}
