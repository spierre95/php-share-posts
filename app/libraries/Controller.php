<?php
  /*
   * Base Controller
   * Loads the models and views
   */
  class Controller {
    // Load model
    public function model($model){
      // Require model file
      require_once '../app/models/' . $model . '.php';

      // Instatiate model
      return new $model();
    }

    // Load view
    public function view($view, $data = []){
      // Check for view file
      if(file_exists('../app/views/' . $view . '.php')){
        require_once '../app/views/' . $view . '.php';
      } else {
        // View does not exist
        die('View does not exist');
      }
    }
  }

//--------------------------------------------------------------------------------------------------------------------------------------------------

//NOTE:

//this did not work because the data variable in views was not defined. The parameters of nested functions are not passed to lower functions like in javascript closures. 

//   //Load model 
//   public function model($model) {
//     $this->requireFile('models', $model);
//     //Instantiate model 
//     return new $model(); 
// }

// //Load view 
// public function view($view, $data = []) {
//     $this->requireFile('views', $view);
// }

//  //check if file exists and require it 
//  private function requireFile($type, $file) {
//     $filePath ='../app/'. $type .'/' .$file .'.php';
//     if(file_exists($filePath)) {
//         require_once $filePath;
//     } else {
//         if($type == 'views'){
//             return die('View does not exist');
//         }
//     }
// }