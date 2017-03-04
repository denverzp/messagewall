<?php

namespace App\Controller;

use App\Engine\Controller;
use App\Engine\Template;
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

        $this->data['post_id'] = 0;

        $this->data['user_id'] = 0;

        if (true === array_key_exists('user_id', $this->session->data)) {
            $this->data['user_id'] = (int)$this->session->data['user_id'];
        }

	    //default template - add new post
	    $template = new Template();

	    $template->data['post_id'] = 0;
	    $template->data['user_id'] = $this->data['user_id'];

	    $this->data['form_template'] = $template->fetch('add_post_form');

        $this->template = 'wall';

        $this->render();
    }
}
