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
        $this->route = 'Home@index';

        //wall
        if (true === array_key_exists('wall', $this->request->get)) {
            $this->route = 'Wall@index';
        }

        //logout
        if (true === array_key_exists('logout', $this->request->get)) {
            $this->route = 'User@logout';
        }

        //Google auth
        if (true === array_key_exists('google', $this->request->get)) {

            if (true === array_key_exists('google_auth', $this->request->get)) {
                $this->route = 'GoogleAuth@auth';
            }

            if (true === array_key_exists('code', $this->request->get)) {
                $this->route = 'GoogleAuth@code_return';
            }

            if (true === array_key_exists('error', $this->request->get)) {
                $this->route = 'GoogleAuth@code_error';
            }
        }
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }
}
