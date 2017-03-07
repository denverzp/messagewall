<?php

namespace App\Controller;

use App\Engine\Controller;
use App\Engine\Template;
use App\Engine\Traits\Strings;
use App\Engine\Traits\Users;
use App\Model\Comment;
use App\Model\Post;

/**
 * Class PostsController.
 */
class PostsController extends Controller
{
    use Strings, Users;

    /**
     * @var array
     */
    private $error = [];

    /**
     * get posts list.
     */
    public function index()
    {
        $page = $this->session->data['page'];

        if(true === array_key_exists('page', $this->request->post)){
            $page = (int) $this->request->post['page'];
        }

        $json = [
            'html' => $this->getPostsByPage($page),
        ];

        $this->jsonAnswer($json);
    }

    /**
     * @param int $page
     *
     * @return array
     */
    public function getPostsByPage($page = 1)
    {
        //this is the limit posts by page
        $limit = 8;

        //sort order
        $order = 'created_at';
        $by = 'DESC';

        $offset = ($page - 1) * $limit;

        $data = [
            'offset' => $offset,
            'limit' => $limit,
            'order' => $order,
            'by' => $by,
        ];

        //posts
        $post = new Post($this->registry);

        $posts = $post->getPosts($data);

        //comments
        $comment = new Comment($this->registry);

        $comments = $comment->getComments($posts);

        //render posts
        $template = new Template();

        $template->data['curr_user'] = $this->isUserId();
        $template->data['posts'] = $posts;
        $template->data['comments'] = $comments;

        return $template->fetch('posts');
    }

    /**
     * show new post form.
     */
    public function create()
    {
        $json = [
            'status' => false,
            'message' => 'You cannot create post',
        ];

        if($this->isUserAuth()){
            $template = new Template();

            $template->data['post_id'] = 0;

            $json = [
                'status' => true,
                'html' => $template->fetch('add_post_form'),
            ];
        }

        $this->jsonAnswer($json);
    }

    /**
     * save new post.
     */
    public function store()
    {
        $json = [
            'status' => false,
            'message' => 'cannot create post',
        ];

        //validate add post form
        if($this->validate()){
            $data = [
                'body' => $this->request->post['body'],
                'user_id' => $this->isUserId(),
            ];

            //model Post - add new post
            $posts = new Post($this->registry);

            $post_id = $posts->addPost($data);

            //render one post
            $template = new Template();

            $template->data['curr_user'] = $this->isUserId();
            $template->data['post'] = $posts->getPost($post_id);

            $json = [
                'status' => true,
                'message' => $post_id,
                'html' => $template->fetch('post'),
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
            'message' => 'You cannot edit this post',
        ];

        if(true === array_key_exists('id', $this->request->post) && $this->isUserAuth()) {
            $id = (int) $this->request->post['id'];

            $posts = new Post($this->registry);

            $post = $posts->getPost($id);

            if($this->isUserId() === (int) $post['user_id']){
                $template = new Template();

                $template->data['body'] = $post['body'];
                $template->data['post_id'] = $post['id'];

                $json = [
                    'status' => true,
                    'html' => $template->fetch('edit_post_form'),
                ];
            } else {
                $json['message'] = 'You cannot edit someone else`s post';
            }
        }

        $this->jsonAnswer($json);
    }

    /**
     * update post.
     */
    public function update()
    {
        $json = [
            'status' => false,
            'message' => 'cannot update  post',
        ];

        //validate add post form
        if($this->validate()){
            if(true === array_key_exists('post_id', $this->request->post)){
                $id = (int) $this->request->post['post_id'];

                $posts = new Post($this->registry);

                $post = $posts->getPost($id);

                if($this->isUserId() === (int) $post['user_id']){
                    $data = [
                        'body' => $this->request->post['body'],
                    ];

                    //model Post - edit post
                    $result = $posts->editPost($id, $data);

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
     * delete post.
     */
    public function destroy()
    {
        $json = [
            'status' => false,
            'message' => 'cannot delete post',
        ];

        if(true === array_key_exists('id', $this->request->post)){
            $id = (int) $this->request->post['id'];

            $posts = new Post($this->registry);

            $post = $posts->getPost($id);

            if($this->isUserId() === (int) $post['user_id']){
                if($posts->deletePost($id)){
                    $json = [
                        'status' => true,
                    ];
                }
            } else {
                $json['message'] = 'You cannot remove someone else`s post';
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
    protected function validate()
    {
        $error = [];

        if(false === $this->isUserAuth()){
            $error['auth'] = 'You need to log in';
        } else {
            if(false === array_key_exists('body', $this->request->post)){
                $error['body'] = 'Post body is required!';
            } else {
                $body_length = $this->getLengtn($this->request->post['body']);

                if($body_length < 2){
                    $error['body'] = 'Post body must be greater than 2 characters!';
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
