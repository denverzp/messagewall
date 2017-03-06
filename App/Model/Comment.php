<?php
/**
 * Created by PhpStorm.
 * User: Ice
 * Date: 06.03.2017
 * Time: 3:20.
 */

namespace App\Model;

use App\Engine\Model;

/**
 * Class Comment.
 */
class Comment extends Model
{
    /**
     * @param array $posts
     *
     * @return array
     */
    public function getComments(array $posts)
    {
        $result = [];

        foreach ($posts as $post) {
            $post_id = (int) $post['id'];

            $result[$post_id] = $this->getCommentsByPost($post_id);
        }

        return $result;
    }

    /**
     * @param $post_id
     *
     * @return array
     */
    public function getCommentsByPost($post_id)
    {
        $result = [];

        $sql = 'SELECT `c`.`id`'
            . ' FROM `comments` c'
            . ' WHERE `c`.`post_id` = ' . (int) $post_id
            . ' ORDER BY `created_at` ASC';

        $query = $this->db->query($sql);

        if($query->num_rows){
            foreach ($query->rows as $row) {
                $result[] = $this->getComment($row['id']);
            }
        }

        return $result;
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function getComment($id)
    {
        $result = [];

        $sql = 'SELECT `c`.`id`, `c`.`body`, `c`.`user_id`, `c`.`post_id`, `c`.`parent_id`, `c`.`level`, `u`.`name` as username,'
                . ' DATE_FORMAT(`c`.`created_at`, "%d.%m.%Y %H:%i") as created_at,'
                . ' DATE_FORMAT(`c`.`updated_at`, "%d.%m.%Y %H:%i") as updated_at'
            . ' FROM `comments` c'
            . ' LEFT JOIN `users` u ON `c`.`user_id`=`u`.`id`'
            . ' WHERE `c`.`id`= ' . (int) $id;

        $query = $this->db->query($sql);

        if($query->num_rows){
            $result = $query->row;
        }

        return $result;
    }

    /**
     * @param array $data
     */
    public function addComment(array $data)
    {
        $sql = 'INSERT INTO `comments`'
            . ' SET'
            . ' `body` ="' . $this->db->escape($data['body']) . '",'
            . ' `post_id` ="' . (int) $data['post_id'] . '",'
            . ' `parent_id` ="' . (int) $data['parent_id'] . '",'
            . ' `level` ="' . (int) $data['level'] . '",'
            . ' `user_id` ="' . (int) $data['user_id'] . '"';

        $this->db->query($sql);

        return $this->db->getLastId();
    }

    /**
     * @param array $data
     */
    public function editComment($id, array $data)
    {
        $sql = 'UPDATE `comments`'
            . ' SET'
            . ' `body` ="' . $this->db->escape($data['body']) . '"'
            . ' WHERE `id` ="' . (int) $id . '"';

        return $this->db->query($sql);
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function deleteComment($id)
    {
        $sql = 'DELETE FROM `comments` WHERE `id`="' . (int) $id . '"';

        return $this->db->query($sql);
    }
}
