<?php
namespace App\Engine;

/**
 * Class DB
 * @package App\Engine
 */
class DB
{
	private $link;
	private $log;

	/**
	 * @param $hostname
	 * @param $username
	 * @param $password
	 * @param $database
	 */
	public function __construct($hostname, $username, $password, $database) {

		$this->log = new Log('db.log');

		$this->link = new \mysqli($hostname, $username, $password, $database);

		if (mysqli_connect_error()) {

			$this->log->write('Error: Could not make a database link (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());

			die('Could not make a database connection...');
		}

		$this->link->set_charset('utf8');

		$this->link->query('SET SQL_MODE = ""');
	}

	/**
	 * @param $sql
	 * @return mixed
	 */
	public function query($sql)
	{
		$query = $this->link->query($sql);

		if (!$this->link->errno){

			if (isset($query->num_rows)) {

				$data = [];

				while ($row = $query->fetch_assoc()) {
					$data[] = $row;
				}

				$result = new \stdClass();
				$result->num_rows = $query->num_rows;
				$result->row = isset($data[0]) ? $data[0] : [];
				$result->rows = $data;

				unset($data);

				$query->close();

				return $result;
			} else {
				return true;
			}

		} else {

			$this->log->write('Error: ' . $this->link->error . "\n" . 'Error No: ' . $this->link->errno . "\n" . $sql);

			return false;
		}
	}

	/**
	 * @param $value
	 * @return mixed
	 */
	public function escape($value)
	{
		return $this->link->real_escape_string($value);
	}

	/**
	 * @return mixed
	 */
	public function countAffected()
	{
		return $this->link->affected_rows;
	}

	/**
	 * @return mixed
	 */
	public function getLastId()
	{
		return $this->link->insert_id;
	}

	public function __destruct() {
		$this->link->close();
	}
}