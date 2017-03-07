<?php

namespace Tests;

use App\Model\Comment;

require_once ('TestCase.php');

/**
 * Only for access to protected method
 * Class testComment
 * @package Tests
 */
class testComment extends Comment
{
	/**
	 * @param $post_id
	 * @param array $comments
	 * @return array
	 */
	public function testGetCommentChildren($post_id, array $comments)
	{
		return $this->getCommentChildren($post_id, $comments);
	}

	/**
	 * @param array $comments
	 * @return array
	 */
	public function testGetTreeViewComments(array $comments)
	{
		return $this->getTreeViewComments($comments);
	}
}

/**
 * Class CommentsTest
 * @package Tests
 */
class CommentsTest extends TestCase
{
	/**
	 *
	 */
	public function testGetCommentChildren()
	{
		$comment = new testComment($this->registry);

		//give
		$comments = [
			0 => [
				0 => ['id' => 1,'parent_id' =>	0, 'name' => 'first',   'level'=> 1],
				1 => ['id' => 4,'parent_id' =>	0, 'name' => 'four',    'level'=> 1],
			],
			1 => [
				0 => ['id' => 2,'parent_id' =>	1, 'name' => 'second',  'level'=> 2],
				1 => ['id' => 3,'parent_id' =>	1, 'name' => 'third',   'level'=> 2],
			],
			4 => [
				0 => ['id' => 5,'parent_id' =>	4, 'name' => 'five',    'level'=> 2],
			],
			5 => [
				0 => ['id' => 6,'parent_id' =>	5, 'name' => 'six',     'level'=> 3],
			],
			6 => [
				0 => ['id' => 7,'parent_id' =>	6, 'name' => 'seven',   'level'=> 4],
			],
		];

		$expected_comments = [
			0 => ['id' => 1,'parent_id' =>	0, 'name' => 'first',   'level'=> 1],
			1 => ['id' => 2,'parent_id' =>	1, 'name' => 'second',  'level'=> 2],
			2 => ['id' => 3,'parent_id' =>	1, 'name' => 'third',   'level'=> 2],
			3 => ['id' => 4,'parent_id' =>	0, 'name' => 'four',    'level'=> 1],
			4 => ['id' => 5,'parent_id' =>	4, 'name' => 'five',    'level'=> 2],
			5 => ['id' => 6,'parent_id' =>	5, 'name' => 'six',     'level'=> 3],
			6 => ['id' => 7,'parent_id' =>	6, 'name' => 'seven',   'level'=> 4],
		];

		//when
		$sort = $comment->testGetCommentChildren(0, $comments);

		//then
		$this->assertEquals($sort, $expected_comments);

		unset($sort, $expected_comments, $comments);
	}

	/**
	 *
	 */
	public function testGetTreeViewComments()
	{
		$comment = new testComment($this->registry);

		//give
		$comments = [
			0 => ['id' => 7,'parent_id' =>	6, 'name' => 'seven',   'level'=> 4],
			1 => ['id' => 2,'parent_id' =>	1, 'name' => 'second',  'level'=> 2],
			2 => ['id' => 6,'parent_id' =>	5, 'name' => 'six',     'level'=> 3],
			3 => ['id' => 3,'parent_id' =>	1, 'name' => 'third',   'level'=> 2],
			4 => ['id' => 1,'parent_id' =>	0, 'name' => 'first',   'level'=> 1],
			5 => ['id' => 5,'parent_id' =>	4, 'name' => 'five',    'level'=> 2],
			6 => ['id' => 4,'parent_id' =>	0, 'name' => 'four',    'level'=> 1],
		];

		$expected_comments = [
			0 => ['id' => 1,'parent_id' =>	0, 'name' => 'first',   'level'=> 1],
			1 => ['id' => 2,'parent_id' =>	1, 'name' => 'second',  'level'=> 2],
			2 => ['id' => 3,'parent_id' =>	1, 'name' => 'third',   'level'=> 2],
			3 => ['id' => 4,'parent_id' =>	0, 'name' => 'four',    'level'=> 1],
			4 => ['id' => 5,'parent_id' =>	4, 'name' => 'five',    'level'=> 2],
			5 => ['id' => 6,'parent_id' =>	5, 'name' => 'six',     'level'=> 3],
			6 => ['id' => 7,'parent_id' =>	6, 'name' => 'seven',   'level'=> 4],
		];

		//when
		$sort = $comment->testGetTreeViewComments($comments);

		//then
		$this->assertEquals($sort, $expected_comments);

		unset($sort, $expected_comments, $comments);
	}
}