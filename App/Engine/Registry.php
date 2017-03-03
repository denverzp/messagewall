<?php
namespace App\Engine;

/**
 * Class Registry
 * @package App\Engine
 */
class Registry
{
	/**
	 * @var array
	 */
	private $data = array();

	/**
	 * @param $key
	 * @return null
	 */
	public function get($key) {
		return (isset($this->data[$key]) ? $this->data[$key] : NULL);
	}

	/**
	 * @param $key
	 * @param $value
	 */
	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	/**
	 * @param $key
	 * @return bool
	 */
	public function has($key) {
		return isset($this->data[$key]);
	}
}