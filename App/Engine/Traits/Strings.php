<?php

namespace App\Engine\Traits;

/**
 * Trait Strings
 * @package App\Engine\Traits
 */
trait Strings
{
	/**
	 * @param $string
	 * @return int
	 */
	 private function getLengtn($string)
	 {
		 return utf8_strlen($string);
	 }
}