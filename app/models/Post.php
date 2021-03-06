<?php

class Post {
    private $db; 

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getPosts(){
        $this->db->query(
            'SELECT * ,
            posts.id as postId,
            users.id as userId,
            posts.created_at as postCreated,
            users.created_at as userCreated
            FROM posts
            INNER JOIN users
            ON posts.user_id = users.id
            ORDER BY posts.created_at DESC'
        );

        return $this->db->resultSet();
    }

    public function addPost($data) {
        $this->db->query('INSERT INTO posts (title, body, user_id) VALUES(:title, :body, :user_id)');
        // bind values [:param => value ]
        $this->db->bind([
            ':title'=>$data['title'], 
            ':body'=>$data['body'], 
            ':user_id'=>$data['user_id'] 
        ]);

        return $this->db->execute() ? true : false;  
    }

    public function updatePost($data) {
        $this->db->query('UPDATE posts SET title = :title, body = :body WHERE id = :id');
        // bind values [:param => value ]
        $this->db->bind([
            ':id'=>$data['id'], 
            ':title'=>$data['title'], 
            ':body'=>$data['body'] 
        ]);

        return $this->db->execute() ? true : false;  
    }

    public function getPostById($id) {
        $this->db->query('SELECT * FROM posts WHERE id = :id');
        $this->db->bind([':id'=> $id]);

        return $this->db->getSingleRow();

    }

    
    public function deletePost($id) {
        $this->db->query('DELETE FROM posts WHERE id = :id');
        $this->db->bind([':id'=> $id]);

        return $this->db->execute() ? true : false;

    }
}