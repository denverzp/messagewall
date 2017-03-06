<?php

namespace App\Controller;

use App\Engine\Controller;

/**
 * Class UserController.
 */
class UserController extends Controller
{
    public function logout()
    {
        session_destroy();

        $this->redirect(HTTP_SERVER);
    }
}
