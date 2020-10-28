<?php

class DataBase{
    private $host = 'localhost';
    private $db = 'test';
    private $user = 'root';
    private $pass = '';
    private $charset = 'utf8mb4';
    private $dsn;

    public function __construct(){
        $this->dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
    }

    public function getHost(){
        return $this->host;
    }

    public function getDB(){
        return $this->test;
    }

    public function getUser(){
        return $this->user;
    }

    public function getPassword(){
        return $this->pass;
    }

    public function getCharset(){
        return $this->charset;
    }

    public function getDSN(){
        return $this->dsn;
    }
}
?>