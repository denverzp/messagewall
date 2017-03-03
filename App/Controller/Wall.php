<?php

namespace App\Controller;

use App\Engine\Controller;

/**
 * Class Board
 * @package App\Controller
 */
class Wall extends Controller
{
	/**
	 *
	 */
	public function index()
	{
		$this->template = 'wall';

		$this->render();
	}
}