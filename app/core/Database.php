<?php

class Database 
{
    public $error;
    private $_host = DB_HOST;
    private $_user = DB_USER;
    private $_pass = DB_PASS;
    private $_db_name = DB_NAME;
    private $_conn;
    private $_stmt;

    public function __construct()
    {
        $dsn = 'mysql:host=' . $this->_host . ';dbname=' . $this->_db_name;

        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        try {
            $this->_conn = new PDO($dsn, $this->_user, $this->_pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die($this->error);
        }
    }

    public function query($query)
    {
        try {
            $this->_stmt = $this->_conn->prepare($query);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die($this->error);
        }
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
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
        $this->_stmt->bindValue($param, $value, $type);
    }

    public function execute()
    {
        try {
            return $this->_stmt->execute();
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die($this->error);
        }
    }
    
    public function resultSet()
    {
        $this->execute();
        return $this->_stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single()
    {
        $this->execute();
        return $this->_stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function prepare($query) {
        return $this->_conn->prepare($query);
    }

    public function getLastInsertId()
    {
        return $this->_conn->lastInsertId();
    }
}
