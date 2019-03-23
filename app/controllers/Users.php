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