<?php


class Pages extends Controller {
    public function __construct()
    {
    }

    public function index() {

        if(isLoggedIn()) {
            redirect('posts');
        }
        
        $data = [
            'title' => 'SharePosts',
            'description' => 'Simple social network built on custom PHP MVC framework'
        ];

        //access to view method because Pages extends main Controller class, requires view if it exists and passes $data array to view 
        $this->view('pages/index', $data);
    }

    public function about() {
        $data = [
            'title' => 'About us',
            'description' => 'App to share posts with other users'
        ];


        $this->view('pages/about', $data);
    }
}