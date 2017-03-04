<?php
namespace App\Controller;

use App\Engine\Controller;

class Home extends Controller
{
    public function index()
    {
        $this->template = 'home';

        $this->render();
    }
}
