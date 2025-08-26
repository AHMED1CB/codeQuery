<?php

namespace App\Database;

class Connection{

    private $user;
    private $password;
    private $host;
    private $port;
    private $database;

    public $connection;

    
    public function __construct(){
    
        $conf = require(dirname(__FILE__ , 3)) . '/config/database.php';
        $conf = $conf['mysql'];

        $this->user = $conf['user'];
        $this->password = $conf['password'];
        $this->host = $conf['host'];
        $this->port = $conf['port'];
        $this->database = $conf['database'];

        $this->connect();


    }

    protected function connect(){

        $dsn = "mysql:host={$this->host};dbname={$this->database}";

        try{
            $pdo = new \PDO($dsn , $this->user , $this->password);
            
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $this->connection = $pdo;
        }catch(\PDOException $e){
            $pdo = null;
            print_r("Connection Error");

        }



    }

}
