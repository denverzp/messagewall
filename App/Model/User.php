<?php
namespace App\Model;


use App\Engine\Model;

/**
 * Class User
 * @package App\Model
 */
class User extends Model
{
	/**
	 * @param $data
	 * @return mixed
	 */
	public function create($data)
	{
		$sql = 'INSERT INTO `users` '
			. ' SET'
			. ' `external_id` = "' . $this->db->escape($data['id']) . '",'
			. ' `name` = "' . $this->db->escape($data['name']) . '",'
			. ' `email` = "' . $this->db->escape($data['email']) . '",'
			. ' `image` = "' . $this->db->escape($data['image']) . '",'
			. ' `gender` = "' . $this->db->escape($data['gender']) . '",'
			. ' `language` = "' . $this->db->escape($data['language']) . '",'
			. ' `url` = "' . $this->db->escape($data['url']) . '"';

		$this->db->query($sql);

		return $this->db->getLastId();

	}

	/**
	 * @param $id
	 * @return array
	 */
	public function get($id)
	{
		$result = [];

		$sql = 'SELECT *'
			. ' FROM `users`'
			. ' WHERE `external_id`="' . $this->db->escape($id) .'"'
			. ' LIMIT 1';

		$query = $this->db->query($sql);

		if($query->num_rows){
			$result = $query->row;
		}

		return $result;
	}
}