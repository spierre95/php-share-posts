<?php 

/* 
    App Core Class 
    Creates URL & loads core controller 
    URL FORMAT - /controller/method/params

    url[0] = controller
    url[1] = method in controller
    url[2] = params 
*/

class Core {
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->getUrl();

        //Look in controllers folder for first value in url array (controller)
        if(file_exists('../app/controllers/' . ucwords($url[0]). '.php')) {
            //if it exists, set as current controller 
            $this->currentController = ucwords($url[0]);
            //unset 0 index
            unset($url[0]);
        }

        //require file from controllers folder (default is Pages )
        require_once '../app/controllers/' . $this->currentController. '.php';

        //instantiate controller object
        $this->currentController = new $this->currentController;

        //Check for second part of url or method (default is index)
        if(isset($url[1])){
            //Check to see if method exists in controller
            if(method_exists($this->currentController, $url[1])) {
                //set as current method
                $this->currentMethod = $url[1];
                //unset 1 index (method)
                unset($url[1]);
            }
        }

        //Get params 
        $this->params = $url ? array_values($url) : [];

        // calls a callback that passes the params to the current method in the current controller 
        call_user_func_array(array($this->currentController, $this->currentMethod), $this->params);

    }


    // get query string ?url= ( This was changed in the htaccess file, so that it is just the end of the root url )
    // www.mcvintro.com/controller/method/params
    public function getUrl(){
        if(isset($_GET['url'])) {
            //remove / form end of string if it exists
            $url = rtrim($_GET['url'], '/');
            //sanitize url (removes any characters that should not be in url)
            $url = filter_var($url, FILTER_SANITIZE_URL);
            // turn url into an array on each '/'
            $url = explode('/', $url);
            return $url;
        }
    }
}