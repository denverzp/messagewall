<?php

namespace App\Engine;

/**
 * very very simple routing
 */
class Route
{
    /**
     * @var string
     */
    protected $route;

    /**
     * @var Request
     */
    protected $request;

    /**
     * Route constructor.
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        //access to Request
        $this->request = $registry->get('request');

        //default route
        $this->route = 'HomeController@index';

        //wall
        if (true === array_key_exists('wall', $this->request->get)) {

            $this->route = 'WallController@index';

	        //AJAX - posts and comments
	        if (true === array_key_exists('type', $this->request->post)) {

		        if($this->request->post['type'] === 'posts'){

			        if(true == array_key_exists('action', $this->request->post)){

				        switch($this->request->post['action']){

					        case 'list':
						        $this->route = 'PostsController@index';
						        break;

					        case 'create':
						        $this->route = 'PostsController@create';
						        break;

					        case 'store':
						        $this->route = 'PostsController@store';
						        break;

					        case 'edit':
						        $this->route = 'PostsController@edit';
						        break;

					        case 'update':
						        $this->route = 'PostsController@update';
						        break;

					        case 'show':
						        $this->route = 'PostsController@show';
						        break;

					        case 'destroy':
						        $this->route = 'PostsController@destroy';
						        break;
				        }
			        }
		        }

		        if($this->request->post['type'] === 'comments'){

			        switch($this->request->post['action']){

				        case 'list':
					        $this->route = 'CommentsController@index';
					        break;

				        case 'create':
					        $this->route = 'CommentsController@create';
					        break;

				        case 'store':
					        $this->route = 'CommentsController@store';
					        break;

				        case 'edit':
					        $this->route = 'CommentsController@edit';
					        break;

				        case 'update':
					        $this->route = 'CommentsController@update';
					        break;

				        case 'show':
					        $this->route = 'CommentsController@show';
					        break;

				        case 'destroy':
					        $this->route = 'CommentsController@destroy';
					        break;
			        }
		        }

	        }
        }

        //logout
        if (true === array_key_exists('logout', $this->request->get)) {
            $this->route = 'UserController@logout';
        }

        //Google auth
        if (true === array_key_exists('google', $this->request->get)) {

            if (true === array_key_exists('google_auth', $this->request->get)) {
                $this->route = 'GoogleAuthController@auth';
            }

            if (true === array_key_exists('code', $this->request->get)) {
                $this->route = 'GoogleAuthController@code_return';
            }

            if (true === array_key_exists('error', $this->request->get)) {
                $this->route = 'GoogleAuthController@code_error';
            }
        }
    }

    /**
     * Controller@return string
     */
    public function getRoute()
    {
        return $this->route;
    }
}
