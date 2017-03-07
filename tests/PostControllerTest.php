<?php

namespace Tests;

use App\Controller\PostsController;

require_once ('TestCase.php');

/**
 * Only for access to protected method
 * Class testPostController
 * @package Tests
 */
class testPostController extends PostsController
{
	/**
	 * @return bool
	 */
	public function testValidate($auth, $body)
	{
		$this->session->data['auth'] = $auth;

		$this->request->post['body'] = $body;

		return $this->validate();
	}
}

/**
 * Class PostControllerTest
 * @package Tests
 */
class PostControllerTest  extends TestCase
{

	/**
	 * @dataProvider addPostsData
	 */
	public function testValidate($auth, $body, $expected)
	{
		$posts = new testPostController($this->registry);

		$result = $posts->testValidate($auth, $body);

		$this->assertEquals($expected, $result);
	}

	/**
	 * @return array
	 */
	public function addPostsData(){
		return  [
			'no_auth'       => [false, 'test', false],
			'no_body'       => [true, '', false],
			'short_body'    => [true, 'q', false],
			'all_good'      => [true, 'test', true],
		];
	}
}