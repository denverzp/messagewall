<?php
/**
 * Created by PhpStorm.
 * User: Ice
 * Date: 06.03.2017
 * Time: 3:20
 */

namespace App\Model;


use App\Engine\Model;

/**
 * Class Comment
 * @package App\Model
 */
class Comment extends Model
{
	/**
	 * @param $post_id
	 * @return array
	 */
	public function getCommentsByPost($post_id)
	{
		$result = [];

		$sql = 'SELECT * FROM `comments` WHERE `post_id` = ' . (int)$post_id;

		$query = $this->db->query($sql);

		if($query->num_rows){
			$result = $query->rows;
		}

		return $result;
	}

	/**
	 * @param array $posts
	 * @return array
	 */
	public function getComments(array $posts)
	{
		$result = [];

		foreach ($posts as $post) {

			$post_id = (int)$post['id'];

			$result[$post_id] = $this->getCommentsByPost($post_id);
		}


		return $result;
	}
}