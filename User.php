<?php
class User
{
    public $id;
    public $username;
    public $email;
    public $password;
    public $isAdmin;

    public function __construct($id, $username, $email, $password, $isAdmin)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->isAdmin = $isAdmin;
    }
    public function saveToDatabase($dbConn)
    {
        $sql = "INSERT INTO users (id, username, email, password, isAdmin) VALUES ($this->id, '$this->username', '$this->email', '$this->password', $this->isAdmin)";
        if (mysqli_query($dbConn, $sql)) {
            return true;
        } else {
            return false;
        }
    }
    public function ifExists($dbConn)
    {
        $sql = "SELECT * FROM users WHERE username = '$this->username' OR email = '$this->email' OR id = $this->id";
        $result = mysqli_query($dbConn, $sql);
        return mysqli_num_rows($result) > 0;
    }
    public function isAdmin($dbConn)
    {
        $sql = "SELECT isAdmin FROM users WHERE id = $this->id";
        $result = mysqli_query($dbConn, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['isAdmin'] == 1;
        }
        return false;
    }
}
