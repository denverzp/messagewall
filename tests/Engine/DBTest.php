<?php

namespace Tests\Engine;

use \PHPUnit\Framework\TestCase  as BaseTestCase;

/**
 * Class DBTest
 * @package Tests\Engine
 */
class DBTest extends BaseTestCase
{
	/**
	 * @dataProvider addGoodInitData
	 * @param $data
	 */
	public function testSuccessInit($data)
	{

		$db = new \App\Engine\DB($data[0], $data[1], $data[2], $data[3]);

		$this->assertInternalType('object', $db);
		$this->assertObjectHasAttribute('link', $db);
		$this->assertObjectHasAttribute('log', $db);
		$this->assertAttributeInternalType('object', 'link', $db);
		$this->assertAttributeInternalType('object', 'log', $db);
	}

	/**
	 * @return array
	 */
	public function addGoodInitData()
	{
		$db_host = getenv('DB_HOST');
		$db_user = getenv('DB_USERNAME');
		$db_pass = getenv('DB_PASSWORD');
		$db_base = getenv('DB_DATABASE');

		return [
			'good'  => [[$db_host, $db_user, $db_pass, $db_base]]
		];
	}

	/**
	 * @expectedException \ErrorException
	 * @dataProvider addWrongInitData
	 * @param $data
	 */
	public function testWrongInit($data)
	{
		$db = new \App\Engine\DB($data[0], $data[1], $data[2], $data[3]);
	}

	/**
	 * @return array
	 */
	public function addWrongInitData()
	{
		$db_host = getenv('DB_HOST');
		$db_user = getenv('DB_USERNAME');
		$db_pass = getenv('DB_PASSWORD');
		$db_base = getenv('DB_DATABASE');

		return [
			'all_wrong' => [['0.0.0.0', 'error_user', '', 'some_database']],
			'wrong_host' => [['0.0.0.0', $db_user, $db_pass, $db_base]],
			'wrong_user' => [[$db_host, 'error_user', $db_pass, $db_base]],
			'wrong_pass' => [[$db_host, $db_user, md5('testpass'), $db_base]],
			'empty_pass' => [[$db_host, $db_user, '', $db_base]],
			'wrong_db' => [[$db_host, $db_user, $db_pass, 'some_database']],
		];
	}
}