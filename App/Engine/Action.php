<?php

namespace App\Engine;

/**
 * 
 */
class Action
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var mixed
     */
    protected $class;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var array
     */
    protected $args = [];

    /**
     * Action constructor.
     * @param $route
     * @param array $args
     */
    public function __construct($route, $args = [])
    {
        $path = '';

        $parts = explode('/', str_replace('../', '', $route));

        foreach ($parts as $part) {
            $path .= $part;

            if (is_file(DIR_APPLICATION . 'Controller/' . str_replace('../', '', $path) . '.php')) {
                $this->file = DIR_APPLICATION . 'Controller/' . str_replace('../', '', $path) . '.php';

                $this->class = '\\App\\Controller\\'. preg_replace('/[^a-zA-Z0-9]/', '', $path);

                array_shift($parts);

                break;
            }

            if ($args) {
                $this->args = $args;
            }
        }

        $method = array_shift($parts);

        if ($method) {
            $this->method = $method;
        } else {
            $this->method = 'index';
        }
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getArgs()
    {
        return $this->args;
    }



   /**
    * 
    * @param string $action
    */
    public function run($action)
    {
        switch ($action) {

            //user try auth wia google
            case 'google_auth':

                //if already auth user try again auth - return to board
                if (isset($_SESSION['auth']) && true === $_SESSION['auth']) {
                    $this->redirect(HTTP_SERVER .'?board');
                } else {
                    $google = new GoogleAuth();

                    $google->auth();
                }

                break;
            
            //return success auth
            case 'auth_return':
                
                if (!isset($_SESSION['auth']) || true !== $_SESSION['auth']) {
                    $google = new GoogleAuth();
                    
                    $token = $google->getToken();
                    
                    if ($token) {
                        $_SESSION['auth_token'] = $token;
                        $_SESSION['auth'] = true;
                    }
                }

//                $this->redirect(HTTP_SERVER .'?board');
                
                break;
            

            //show board
            //return auth error
            case 'show_board':
            case 'auth_error':

                $front = new Front();

                $front->board();

                break;

            //show homepage
            case 'show_home':
            default:
                
                //if auth user - redirect to board
                if (isset($_SESSION['auth']) && true === $_SESSION['auth']) {
                    $this->redirect(HTTP_SERVER .'?board');
                }

                $front = new Front();

                $front->home();

                break;
        }
    }
}
