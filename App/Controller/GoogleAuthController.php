<?php

namespace App\Controller;

use App\Engine\Controller;
use App\Model\GoogleAuth;
use App\Model\User;

/**
 * Class GoogleAuthController
 * @package App\Controller
 */
class GoogleAuthController extends Controller
{
	/**
	 * User try auth wia Google
	 */
	public function auth()
	{
		//if already auth user try again auth - return to board
		if (true === array_key_exists('auth', $this->session->data) && true === $this->session->data['auth']) {

			$this->redirect(HTTP_SERVER .'?wall');

		//auth wia google
		} else {

			$client = new GoogleAuth($this->registry);

			$client->auth();
		}
	}

	/**
	 * return answer from OAuth 2.0 server
	 */
	public function code_return()
	{
		if(true === array_key_exists('code', $this->request->get)){

			$client = new GoogleAuth($this->registry);

			//get access token from auth code
			$token = $client->getToken($this->request->get['code']);

			if(false !== $token){

				$this->session->data['auth'] = true;
				$this->session->data['auth_token'] = $token;

				//need get userinfo from google
				$userinfo = $client->userinfo();

				if($userinfo){

					$this->get_user($userinfo);
				}
			}
		}

		$this->redirect(HTTP_SERVER . '?wall');
	}

	/**
	 * @param array $userinfo
	 */
	private function get_user(array $userinfo)
	{
		$user = new User($this->registry);

		//if this user already isset in db - get user_id
		$user_db = $user->get($userinfo['id']);

		//otherwise - create new user and get user_id
		if(0 === count($user_db)){

			$user_id = $user->create($userinfo);

			$this->session->data['userinfo'] = $userinfo;

			$this->session->data['user_id'] = $user_id;

		} else {

			$this->session->data['userinfo'] = $user_db;

			$this->session->data['user_id'] = $user_db['id'];
		}
	}

	/**
	 * return error from OAuth 2.0 server
	 */
	public function code_error()
	{
		if(true === array_key_exists('error', $this->request->get)){

			$this->session->data['error'] = $this->request->get['error'];

			$this->redirect(HTTP_SERVER . '?wall');
		}
	}
}