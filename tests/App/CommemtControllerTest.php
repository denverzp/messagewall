<?php

namespace Tests\App;

use App\Controller\CommentsController;
use Tests\TestCase;


/**
 * Only for access to protected method
 * Class testPostController
 * @package Tests
 */
class testCommentsController extends CommentsController
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
class CommemtControllerTest  extends TestCase
{

	/**
	 * @dataProvider addCommemtsData
	 */
	public function testValidate($auth, $body, $expected)
	{
		$comments = new testCommentsController($this->registry);

		$result = $comments->testValidate($auth, $body);

		$this->assertEquals($expected, $result);
	}

	/**
	 * @return array
	 */
	public function addCommemtsData(){
		return  [
			'no_auth'       => [false,  'test',     false],
			'no_body'       => [true,   '',         false],
			'null'          => [true,   null,       false],
			'false'         => [true,   false,      false],
			'integer'       => [true,   2,          false],
			'short_body'    => [true,   'q',        false],
			'all_good'      => [true,   'test',     true],
		];
	}
}