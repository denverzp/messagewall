<?php
/**
 * Created by PhpStorm.
 * User: Ice
 * Date: 05.03.2017
 * Time: 9:54
 */

namespace App\Model;

use App\Engine\Model;

/**
 * Class Post
 * @package App\Model
 */
class Post extends Model
{

	/**
	 * @param int $post_id
	 * @return array
	 */
	public function getPost($post_id)
	{
		$result = [];

		$sql = 'SELECT `p`.`id`, `p`.`body`, `p`.`user_id`, `u`.`name` as username,'
				. ' DATE_FORMAT(`p`.`created_at`, "%d.%m.%Y %H:%i") as created_at,'
				. ' DATE_FORMAT(`p`.`updated_at`, "%d.%m.%Y %H:%i") as updated_at'
			. ' FROM `posts` p'
			. ' LEFT JOIN `users` u ON `p`.`user_id`=`u`.`id`'
			. ' WHERE `p`.`id`= ' . (int)$post_id;

		$query = $this->db->query($sql);

		if($query->num_rows){
			$result = $query->row;
		}

		return $result;
	}

	/**
	 * @param array $data
	 * @return array
	 */
	public function getPosts(array $data)
	{
		$result = [];

		$sql = 'SELECT `id`'
			. ' FROM `posts`';

		//order by
		$accept_order = [
			'created_at',
			'title'
		];

		if(true === array_key_exists('order', $data) && true === in_array($data['order'], $accept_order, false)){

			$by = 'DESC';

			if(true === array_key_exists('by', $data) && $data['by'] === 'ASC'){
				$by = 'ASC';
			}

			$sql .= ' ORDER BY `' . $data['order'] . '` ' . $by;
		}

		//limit
		if(true === array_key_exists('limit', $data) && true === array_key_exists('offset', $data)){
			$sql .= ' LIMIT ' . $data['offset'] . ',' . $data['limit'];
		}

		//execute query
		$query = $this->db->query($sql);

		if($query->num_rows){

			foreach ($query->rows as $row) {

				$result[] = $this->getPost($row['id']);
			}
		}

		return $result;
	}

	/**
	 * @param array $data
	 */
	public function addPost(array $data)
	{
		$sql = 'INSERT INTO `posts`'
			. ' SET'
			. ' `body` ="' . $this->db->escape($data['body']) . '",'
			. ' `user_id` ="' . (int)$data['user_id'] . '"';

		$this->db->query($sql);

		return $this->db->getLastId();
	}

	/**
	 * @param $id
	 * @param array $data
	 * @return mixed
	 */
	public function editPost($id, array $data)
	{
		$sql = 'UPDATE `posts`'
			. ' SET'
			. ' `body` ="' . $this->db->escape($data['body']) . '"'
			. ' WHERE `id` ="' . (int)$id . '"';

		return $this->db->query($sql);
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public function deletePost($id)
	{
		$sql = 'DELETE FROM `posts` WHERE `id`="' . (int)$id . '"';

		return $this->db->query($sql);
	}
}