<?php

namespace App\Controller;

use App\Engine\Controller;
use App\Engine\Traits\Users;

/**
 * Class WallController.
 */
class WallController extends Controller
{
    use Users;

    public function index()
    {
        $this->data['auth'] = $this->isUserAuth();
        $this->data['userinfo'] = $this->isUserinfo();
        $this->data['user_id'] = $this->isUserId();

        //default page
        $this->session->data['page'] = 1;

        //posts
        $post = new PostsController($this->registry);

        $this->data['posts'] = $post->getPostsByPage($this->session->data['page']);

        $this->template = 'wall';

        $this->render();
    }
}
