<?php 

class Posts extends Controller {

    public function __construct()
    {
        //TODO: move to post posts method, I want to display main view for all posts, just need to be logged in to create posts not view them
        if(!isLoggedIn()){
            redirect('users/login');
        }

        $this->postModel = $this->model('Post');
        $this->userModel = $this->model('User');
    }

    public function index(){

        $posts = $this->postModel->getPosts();

        $data = [
            'posts' => $posts
        ];

        $this->view('posts/index', $data);

    }

    public function add(){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            //Santize the post 
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'user_id' => $_SESSION['user_id'],
                'title_error' => '',
                'body_error' => ''
            ];

            // Validate Data

            if(empty($data['title'])){
                $data['title_error'] = 'Please enter title';
            }

            if(empty($data['body'])){
                $data['body_error'] = 'Please enter body text';
            }


            // NO errors

            if(empty($data['title_error']) && empty($data['title_body'])) {
                //Validated 
                if($this->postModel->addPost($data)){
                    flash('post_message', "Post Added");
                    redirect('posts');
                } else {
                    die('Something went wrong');
                }
            } else {
                //load views with errors
                $this->view('posts/add', $data);
            }

        } else {
            
            $data = [
                'title' => '',
                'body' => ''
            ];
    
            $this->view('posts/add', $data);
        }


    }

    // controller/method/param 
    //example = posts/show/3
    public function show($id) {

        $post = $this->postModel->getPostById($id);
        $user = $this->userModel->getUserById($post->user_id);

        $data = [
            'post' => $post,
            'user' => $user
        ];

        $this->view('posts/show', $data);
    }



    public function edit($id){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            //Santize the post 
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'title_error' => '',
                'body_error' => ''
            ];

            // Validate Data

            if(empty($data['title'])){
                $data['title_error'] = 'Please enter title';
            }

            if(empty($data['body'])){
                $data['body_error'] = 'Please enter body text';
            }


            // NO errors

            if(empty($data['title_error']) && empty($data['title_body'])) {
                //Validated 
                if($this->postModel->updatePost($data)){
                    flash('post_message', "Post Updated");
                    redirect('posts/show/'. $id);
                } else {
                    die('Something went wrong');
                }
            } else {
                //load views with errors
                $this->view('posts/edit', $data);
            }

        } else {
            // gets current post by model 
            $post = $this->postModel->getPostById($id);

            if($post->user_id != $_SESSION['user_id']) {
                redirect('posts/show/'. $id);
            }
            
            $data = [
                'id' => $id,
                'title' => $post->title,
                'body' => $post->body
            ];
    
            $this->view('posts/edit', $data);
        }


    }

    public function delete($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $post = $this->postModel->getPostById($id);

            if($post->user_id != $_SESSION['user_id']) {
                redirect('posts');
            }
            
            if($this->postModel->deletePost($id)){
                flash('post_message', 'post: "'. $post->title .'" was removed');
                redirect('posts');
            } else {
                redirect('posts');
            }

        }
    }


}