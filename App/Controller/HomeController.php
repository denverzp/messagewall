<?php
namespace App\Controller;

use App\Engine\Controller;

class HomeController extends Controller
{
    public function index()
    {
        //if auth user - redirect to wall
        if (isset($this->session->data['auth']) && true === $this->session->data['auth']) {
            $this->redirect(HTTP_SERVER .'?wall');
        }

        $this->template = 'home';

        $this->render();
    }
}
