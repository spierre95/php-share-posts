<?php 


//Load Config 
require_once 'config/config.php';

//Load Helper
require_once 'helpers/url_helper.php';
require_once 'helpers/session_helper.php';

// This still works however if we add new libraries it can get messy autoloading is a much better option. Also there is not need to require the files everytime.

// //load libraries 
// require_once 'libraries/Core.php';
// require_once 'libraries/Controller.php';
// require_once 'libraries/Database.php';

//--------------------------------------------------------------------------------------------------------------------------

//AutoLoad Core Libraries 
//file name needs to match class name in order for spl_autoload_register to work

spl_autoload_register(function($className){
    require_once 'libraries/' .$className . '.php';
});