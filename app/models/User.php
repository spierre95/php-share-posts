<?php

class User {

    public function __construct()
    {
        $this->db = new Database;
    } 

    public function register($data) {
        $this->db->query('INSERT INTO users (name, email, password) VALUES(:name, :email, :password)');
        // bind values [:param => value ]
        $this->db->bind([
            ':name'=>$data['name'], 
            ':email'=>$data['email'], 
            ':password'=>$data['password'] 
        ]);

        return $this->db->execute() ? true : false;  
    }

    public function login($email, $password) {
        
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind([':email'=>$email]);
        $row = $this->db->getSingleRow();

        //hashed password from database
        $hashed_password = $row->password;       

        return password_verify($password, $hashed_password ) ? $row : false;  
    }

    
    public function uploadImage($id) {
        
    }

    //Check if email already exists 
    public function emailExists($email) {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind([':email'=> $email]);
        $this->db->getSingleRow();

        //Check row 
        return ($this->db->rowCount() > 0) ? true : false; 
    }

    public function getUserById($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind([':id'=> $id]);

        return $this->db->getSingleRow();
    }
}