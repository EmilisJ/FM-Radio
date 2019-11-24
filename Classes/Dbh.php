<?php
namespace App;
use \PDO;

//Dbs = Database Handle
abstract class Dbh{

    private $host;
    private $user;
    private $pass;
    private $dbname;
    private $charset;
    private $options;

    public function connect(){
        $this->host = BitRadio::dbHost;
        $this->dbname = BitRadio::dbDataBase;
        $this->user = BitRadio::dbUser;
        // $this->pass = BitRadio::dbPassword;
        $this->pass = "";
        $this->charset = "utf8mb4";
        $this->options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        //dsn = data source name
        $dsn = "mysql:host=".$this->host.";dbname=".$this->dbname.";charset=".$this->charset;
        
        try {
            $pdo = new PDO($dsn, $this->user, $this->pass, $this->options);
            return $pdo;
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}