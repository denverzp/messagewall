<?php

namespace App\Controller;

use App\Engine\Controller;
use App\Model\Comment;

/**
 * Class CommentsController
 * @package App\Controller
 */
class CommentsController extends Controller
{

	public function index()
	{
		if(true === array_key_exists('id', $this->request->post)){

			$post_id = (int)$this->request->post['id'];

			$comment = new Comment($this->registry);

			$comments = $comment->getCommentsByPost($post_id);
		}
	}

	public function create()
	{

	}
	public function store()
	{

	}
	public function edit()
	{

	}
	public function update()
	{

	}
	public function show()
	{

	}
	public function destroy()
	{

	}
}