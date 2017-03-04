<?php
namespace App\Controller;

use App\Engine\Controller;

/**
 * Class User
 * @package App\Controller
 */
class UserController extends Controller
{
    public function logout()
    {
        session_destroy();

        $this->redirect(HTTP_SERVER);
    }
}
