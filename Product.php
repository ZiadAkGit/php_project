<?php
class Product
{
    public $id;
    public $name;
    public $price;
    public $quantity;


    public function __construct($id, $quantity, $name, $price)
    {
        $this->id = $id;
        $this->quantity = $quantity;
        $this->name = $name;
        $this->price = $price;
    }

    public function saveToDatabase($dbConn)
    {
        $sql = "INSERT INTO products (id, name, quantity, price) VALUES ($this->id, '$this->name', $this->quantity, $this->price)";
        if (mysqli_query($dbConn, $sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function ifExists($dbConn)
    {
        $sql = "SELECT * FROM products WHERE name = '$this->name'";
        $result = mysqli_query($dbConn, $sql);
        return mysqli_num_rows($result) > 0;
    }
}
