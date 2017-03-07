<?php

namespace Tests;

$_SERVER['HTTP_HOST'] = 'localhost';

//config
require_once __DIR__ . '/../bootstrap/config.php';

//autoload
require_once __DIR__ . '/../vendor/autoload.php';

//helpers
require_once DIR_APPLICATION . 'Engine/helpers/utf8.php';



use PHPUnit\Framework\TestCase  as BaseTestCase;

/**
 * Class TestCase
 * @package Tests\Unit
 */
class TestCase extends BaseTestCase
{
	public $registry;

	public function setUp()
	{
		//Registry
		$registry = new \App\Engine\Registry();

		//Request
		$request = new \App\Engine\Request();
		$registry->set('request', $request);

		//WARNING - before uncomment - need config mysqld
		//DB_testing
//		$db_host = getenv('DB_HOST');
//		$db_user = getenv('DB_USERNAME');
//		$db_pass = getenv('DB_PASSWORD');
//		$db_base = getenv('DB_DATABASE');
//
//		$db = new \App\Engine\DB($db_host, $db_user, $db_pass, $db_base);
//		$registry->set('db', $db);

		//Session
		$session = new \App\Engine\Session();
		$registry->set('session', $session);

		$this->registry = $registry;
	}
}