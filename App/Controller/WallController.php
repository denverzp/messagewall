<?php

namespace App\Controller;

use App\Engine\Controller;
use App\Model\GoogleAuth;

/**
 * Class Board
 * @package App\Controller
 */
class WallController extends Controller
{
    /**
     *
     */
    public function index()
    {
        $this->data['auth'] = false;

        if (true === array_key_exists('auth', $this->session->data) && true === $this->session->data['auth']) {
            $this->data['auth'] = true;
        }

        $this->data['userinfo'] = false;

        if (true === array_key_exists('userinfo', $this->session->data) && 0 !== count($this->session->data['userinfo'])) {
            $this->data['userinfo'] = $this->session->data['userinfo'];
        }

        $this->template = 'wall';

        $this->render();
    }
}
