<?php 

//TODO: review and refactor login validation and errors

class Users extends Controller {
    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function register() {
        //Check for POST request 
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            //Process form 
           
            //sanitize input 
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $formInput = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password'=> trim($_POST['confirm_password']),
            ];

            $errors = [
                'name_error'=> '',
                'email_error'=> '',
                'password_error'=> '',
                'confirm_password_error'=> '',
            ];

            //validate input
            $data = $this->validateInput('register', $formInput, $errors);

            //Check if there are any errors and load appropriate view 
            if (empty($data['name_error']) && empty($data['email_error']) && empty($data['password_error']) && empty($data['confirm_password_error'])) {
          
                //Hash Password 
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Register User 
                if($this->userModel->register($data)){
                    flash('register_success', 'Congrats ' .$data['name']. '! You are now registered and can login');
                    redirect('users/login');
                } else {
                    die('registration failed');
                }
            
            } else {
                //Load view with errors
                $this->view('users/register', $data);
            }
       
        } else {
            //load form 
            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password'=> '',
                'name_error'=> '',
                'email_error'=> '',
                'password_error'=> '',
                'confirm_password_error'=> '',
            ];

            return $this->view('users/register', $data);
        }
    }
    public function login() {
        //Check for POST

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            //Process Form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                        
            $formInput = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
            ];

            $errors = [
                'name_error'=> '',
                'password_error'=> '',

            ];

            //validate input
            $data = $this->validateInput('login', $formInput, $errors);

            //Check if there are any errors and load appropriate view 
            if ( empty($data['email_error']) && empty($data['password_error'])) {
                //create session
                $this->createUserSession($data['loggedInUser']);
                redirect('posts');
            } else {
                //Load view with errors not specfic to login 
                $this->view('users/login', $data);
            }
             
        } else {
               //load form 
               $data = [
                'email' => '',
                'password' => '',
                'email_error'=> '',
                'password_error'=> '',
            ];

            return $this->view('users/login', $data);
        }
    }

    public function profile($id){

            $user = $this->userModel->getUserById($id);
    
            $data = [
                'user' => $user
            ];
    
            $this->view('users/profile', $data);
    }

   // TODO: 

   public function edit($id) {

    //Check for POST request 
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data = [
            'id' => $id,
            'name' => trim($_POST['name']),
            'image' => $_FILES['image'],
            'location' => trim($_POST['location']),
            'description' => trim($_POST['description']),
            'name_error'=> '',
            'image_error'=> '',
            'location_error'=> '',
            'description_error'=> '',
            'file_ext' => '',
        ];

        //TODO: validate location is valid 
        
        if(empty($data['name'])){
            $data['name_error'] = 'Please enter name';
        }

        if(strlen($data['description']) > 200 ) {
            $data['description_error'] = 'Bio must be less than 200 characters';
        } 

        $data = $this->validateImage($data);

        if (!empty($data['name_error']) && !empty($data['description_error']) && !empty($data['image_error'])){
        
              if($this->userModel->updateProfile($data)){
                  $user = $this->userModel->getUserById($id);
                  flash('profile_update_success', 'Your profile has been successfully updated');
                  redirect('users/profile/'. $id);
              } else {
                  die('registration failed');
              }
          
          } else {
              //Load view with errors
              $this->view('users/edit', $data);
          }
        
    } else {

            $user = $this->userModel->getUserById($id);

            $data = [
                'id' => $id,
                'name' => $user->name,
                'image' => $user->profile_image,
                'location' => $user->location,
                'description' => $user->description,
                'name_error'=> '',
                'image_error'=> '',
                'location_error'=> '',
                'description_error'=> '',
            ];

           
        //load form 
        return $this->view('users/edit', $data);
    }
}
    public function validateImage($data) {
        // Check image

        $fileError = $data['image']['error'];

        if($fileError > 0){
            // error uploading file
            $data['image_error'] = 'upload error';
        }
        $maxSize = 100000;
        $fileType = $data['image']['type'];
        $fileSize = $data['image']['size'];
        $fileTempName = $data['image']['tmp_name'];

        print_r($data['image']);


        $trueFileType = exif_imagetype($fileTempName);
        $allowedFiles = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);
        if (in_array($trueFileType, $allowedFiles)) {
            // file exceed max size
            if($fileSize > $maxSize){
                $data['image_error'] = 'image exceeds max file size';
            }else{
                switch($trueFileType){
                    case 1 : 
                        $data['file_ext']  = ".gif";
                    break;
                    case 2: 
                        $data['file_ext']   = ".jpg";
                    break;
                    case 3 : 
                        $data['file_ext']  = ".png";
                    break;
                }
            }
        }else{
            //wrong file type
            $data['image_error'] = 'wrong file type. PLease upload a png or jpg';
        }

        return $data;
    }

    public function validateInput($form, $formInput, $errors){
        foreach( $formInput as $key => $input) {
            //Empty input
            if(empty($formInput[$key])){
                $errorMsg = $key == 'confirm_password' ? "Please enter confirm password " : 'Please enter '. $key;
                $errors[$key ."_error"] = $errorMsg;
                continue;
            }

            if($form == 'register') {
                switch($key) {
                    case 'email':
                    // email already exists
                    if($this->userModel->emailExists($input)){
                        $errors[$key ."_error"] = 'Email already exists';
                    }
                    break;
                    case 'password':
                    // password length
                        if(strlen($formInput[$key]) < 6 ){
                            $errors[$key ."_error"] = 'Password must be at least 6 characters';
                        }
                    break;
                    case 'confirm_password':
                    // passwords do not match
                        if($formInput[$key] != $formInput['password'] ){
                            $errors[$key ."_error"] = 'Passwords do not match';
                        }
                    break;
                    default:
                    break;
                }
            }

            if($form == 'login'){
                switch($key) {
                    case 'email':
                    // email does not exist
                    if(!$this->userModel->emailExists($formInput[$key])){
                        $errors[$key .'_error'] = "no user found";
                    }
                    break;
                    case 'password':
                        // validate password
                        $loginUser = $this->userModel->login($formInput['email'], $formInput['password']);
                        
                        if($loginUser) {
                            $formInput['loggedInUser'] = $loginUser;
                        } else {
                            $errors[$key .'_error'] = "Incorrect Password";
                        }
                    break;
                    default:
                    break;
                }
            }
        };

        $data = array_merge($formInput, $errors);

        return $data;
    }

    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
    }

    public function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['email']);
        unset($_SESSION['name']);
        session_destroy();
        redirect('users/login');
    } 
}