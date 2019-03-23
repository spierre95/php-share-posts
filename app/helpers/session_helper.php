<?php 

session_start();

function flash($name = '', $message= '', $class = 'alert alert-success') {
    if(!empty($name)) {

        // set name to message in controller ex. flash('register_success', 'You are now registered')
        if(!empty($message) && empty($_SESSION[$name])){

            //unset previous name if it exists
            if(!empty($_SESSION[$name])){
                unset($_SESSION[$name]);
            }

            //unset previous class name if it exists
            if(!empty($_SESSION[$name. '_class'])){
                unset($_SESSION[$name. '_class']);
            }
            
            // set the name key to the message that was created in the controller
            $_SESSION[$name] = $message;

            //set class name 
            $_SESSION[$name. '_class'] = $class;
            
        // display in view - flash('register_success)
        } elseif((empty($message) && !empty($_SESSION[$name]))) {
            
            //set class name
            $class = !empty($_SESSION[$name. '_class']) ?  $_SESSION[$name. '_class'] : '';
            
            //display flash message 
            echo '<div class="'.$class.'" id="msg-flash">'.$_SESSION[$name].'</div>';

            //unset session name and class
            unset($_SESSION[$name]);
            unset($_SESSION[$name. '_class']);
        }
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) ? true : false; 
}