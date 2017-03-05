<?php

namespace App\Controller;

use App\Engine\Controller;
use App\Engine\Template;
use App\Engine\Traits\Users;
use App\Model\Post;

/**
 * Class WallController
 * @package App\Controller
 */
class WallController extends Controller
{
	use Users;

    /**
     *
     */
    public function index()
    {
	    //DEV only
	    $this->session->data['auth'] = true;
	    $this->session->data['user_id'] = 1;
	    $this->session->data['userinfo'] = [
		    'name' => 'DEV Denver',
		    'image' => '',
	    ];
	    //DEV only

        $this->data['auth'] = $this->isUserAuth();
        $this->data['userinfo'] = $this->isUserinfo();
        $this->data['user_id'] = $this->isUserId();

	    //posts
	    $post = new Post($this->registry);

	    $data = [
		    'offset'=> 0,
		    'limit' => 9,
		    'order' => 'created_at',
		    'by'    => 'DESC'
	    ];

	    $posts = $post->getPosts($data);

	    //render posts
	    $template = new Template();

	    $template->data['posts'] = $posts;
	    $template->data['curr_user'] = $this->isUserId();

	    $this->data['posts'] = $template->fetch('posts');

        $this->template = 'wall';

        $this->render();
    }
}
