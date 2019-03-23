<?php 
/**
 * PDO Database Class
 * 
 * Connect to database 
 * Create prepared statements
 * Bind Values 
 * Return rows and results 
 * 
 */

 class Database {
     private $host = DB_HOST;
     private $dbname = DB_NAME;
     private $user = DB_USER;
     private $password = DB_PASS;

     //database handler (PDO)
     private $dbh;
     //prepared statement generated using PDO 
     private $stmt;
     
     private $error;

     public function __construct()
     {
        //Setup PDO connection 
        $dsn = 'mysql:host=' . $this->host . ';dbname='. $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
         try{  
            $this->dbh = new PDO($dsn, $this->user, $this->password, $options);
         }catch(PDOException $error){
             $this->error = $error->getMessage();
             echo "ERROR!: ". $this->error . "<br/>";
             die();
         };
     }

    // generate prepared statement by passing sql to database handler (PDO)
    public function query($sql) {
         $this->stmt = $this->dbh->prepare($sql);     
    }

    //Bind values in prepared statement

    // entered as an associative array  input = [:param => value ]
    public function bind($input, $type = null){
        foreach($input as $param => $value){
            //if no type, set one based on value
            if(is_null($type)){
                switch(true){
                    case is_int($value):
                        $type = PDO::PARAM_INT;
                        break;
                    case is_bool($value):
                        $type = PDO::PARAM_BOOL;
                        break;
                    case is_null($value):
                        $type = PDO::PARAM_NULL;
                        break;
                    default:
                        $type = PDO::PARAM_STR;
                }
            }
            // PDO binds values to :named or ? placeholders in SQL statement that was used to prepare the statement. 
            // example: $this->stmt->bindValue(':color', $color, PDO::PARAM_STR)
            // binds $color to :color
            $this->stmt->bindValue($param, $value, $type);
        } 
    }

    // Execute the prepared statement 
    public function execute(){
        //PDO method 
        return $this->stmt->execute();
    }

    //get result set as array of objects 
    public function resultSet() {
        $this->execute();
        //PDO Method
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // get single record as object
    public function getSingleRow(){
        $this->execute();
        //PDO Method
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    // get row count
    public function rowCount(){
        //PDO Method
        return $this->stmt->rowCount();
    }


 }