<?php

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getUser($data){
        $username = $data['username'];
        $password = md5($data['password']);

        // Use prepared statements to prevent SQL injection
        $this->db->query("SELECT * FROM user WHERE username = :username AND password = :password");
        $this->db->bind(':username', $username);
        $this->db->bind(':password', $password);

        // Execute the query
        $result = $this->db->single(); // Assuming you have a single() method to fetch a single result

        // Check for errors
        if ($this->db->error) {
            // Handle the error (you might want to log it)
            echo "Database error: " . $this->db->error;
        }

        return $result;
    }

}