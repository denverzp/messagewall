<?php

namespace App\Controller;

use App\Engine\Controller;
use App\Engine\Template;
use App\Engine\Traits\Strings;
use App\Engine\Traits\Users;
use App\Model\Comment;

/**
 * Class CommentsController.
 */
class CommentsController extends Controller
{
    use Strings, Users;

    public function index()
    {
        if(true === array_key_exists('id', $this->request->post)){
            $post_id = (int) $this->request->post['id'];

            $comment = new Comment($this->registry);

            $comments = $comment->getCommentsByPost($post_id);
        }
    }

    /**
     * show new comment form.
     */
    public function create()
    {
        $json = [
            'status' => false,
            'message' => 'You cannot create comment',
        ];

        if($this->isUserAuth()){
            $template = new Template();

            $template->data['post_id'] = 0;
            $template->data['parent_id'] = 0;
            $template->data['level'] = 1;

            if(true === array_key_exists('post_id', $this->request->post) && ! empty($this->request->post['post_id'])){
                $template->data['post_id'] = $this->request->post['post_id'];
            }

            if(true === array_key_exists('parent_id', $this->request->post) && ! empty($this->request->post['parent_id'])){
                $template->data['parent_id'] = $this->request->post['parent_id'];
            }

            if(true === array_key_exists('level', $this->request->post) && ! empty($this->request->post['level'])){
                $template->data['level'] = $this->request->post['level'];
            }

            $json = [
                'status' => true,
                'html' => $template->fetch('add_comment_form'),
            ];
        }

        $this->jsonAnswer($json);
    }

    /**
     * save new comment.
     */
    public function store()
    {
        $json = [
            'status' => false,
            'message' => 'cannot create comment',
        ];

        //validate add comment form
        if($this->validate()){
            $data = [
                'body' => $this->request->post['body'],
                'post_id' => $this->request->post['post_id'],
                'parent_id' => $this->request->post['parent_id'],
                'level' => $this->request->post['level'],
                'user_id' => $this->isUserId(),
            ];

            //model Comment - add new comment
            $comments = new Comment($this->registry);

            $comment_id = $comments->addComment($data);

            //render one comment
            $template = new Template();

            $template->data['curr_user'] = $this->isUserId();
            $template->data['comment'] = $comments->getComment($comment_id);

            $json = [
                'status' => true,
                'message' => $comment_id,
                'html' => $template->fetch('comment'),
            ];
        } else {
            $json['error'] = $this->error;
        }

        $this->jsonAnswer($json);
    }

    public function edit()
    {
        $json = [
            'status' => false,
            'message' => 'You cannot edit this comment',
        ];

        if(true === array_key_exists('id', $this->request->post) && $this->isUserAuth()) {
            $id = (int) $this->request->post['id'];

            $comments = new Comment($this->registry);

            $comment = $comments->getComment($id);

            if($this->isUserId() === (int) $comment['user_id']){
                $template = new Template();

                $template->data['body'] = $comment['body'];
                $template->data['comment_id'] = $comment['id'];
                $template->data['post_id'] = $comment['post_id'];
                $template->data['parent_id'] = $comment['parent_id'];

                $json = [
                    'status' => true,
                    'html' => $template->fetch('edit_comment_form'),
                ];
            } else {
                $json['message'] = 'You cannot edit someone else`s post';
            }
        }

        $this->jsonAnswer($json);
    }

    public function update()
    {
        $json = [
            'status' => false,
            'message' => 'cannot update comment',
        ];

        //validate add post form
        if($this->validate()){
            if(true === array_key_exists('id', $this->request->post)){
                $id = (int) $this->request->post['id'];

                $comments = new Comment($this->registry);

                $comment = $comments->getComment($id);

                if($this->isUserId() === (int) $comment['user_id']){
                    $data = [
                        'body' => $this->request->post['body'],
                    ];

                    //model Post - edit post
                    $result = $comments->editComment($id, $data);

                    if($result){
                        $json = [
                            'status' => true,
                        ];
                    }
                } else {
                    $json['message'] = 'You cannot edit someone else`s post';
                }
            }
        } else {
            $json['error'] = $this->error;
        }

        $this->jsonAnswer($json);
    }

    /**
     * delete comment.
     */
    public function destroy()
    {
        $json = [
            'status' => false,
            'message' => 'cannot delete comment',
        ];

        if(true === array_key_exists('id', $this->request->post)){
            $id = (int) $this->request->post['id'];

            $comments = new Comment($this->registry);

            $comment = $comments->getComment($id);

            if($this->isUserId() === (int) $comment['user_id']){
                if($comments->deleteComment($id)){
                    $json = [
                        'status' => true,
                    ];
                }
            } else {
                $json['message'] = 'You cannot remove someone else`s comment';
            }

            $this->jsonAnswer($json);
        }
    }

    /**
     * @param array $json
     */
    private function jsonAnswer(array $json)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($json);
    }

    /**
     * @return bool
     */
    private function validate()
    {
        $error = [];

        if(false === $this->isUserAuth()){
            $error['auth'] = 'You need to log in';
        } else {
            if(false === array_key_exists('body', $this->request->post)){
                $error['body'] = 'Comment body is required!';
            } else {
                $body_length = $this->getLengtn($this->request->post['body']);

                if($body_length < 2){
                    $error['body'] = 'Comment body must be greater than 2 characters!';
                }
            }
        }

        if(0 !== count($error)){
            $this->error = $error;

            return false;
        }

        return true;
    }
}
