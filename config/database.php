<?php
   
class Database {
   // Database login
   private $host = 'localhost';
   private $db_name = 'courses';
   private $username = 'courses';
   private $password = 'password';

   // Other params
   public $conn;

   // Connect
   public function connect() {
      $this->conn = null;

      try {
         $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
         $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Error reporting
      } catch(PDOException $exception) {
         echo 'Couldn\'t connect to database: ' . $exception->getMessage();
      }

      return $this->conn;
   }

   // Disconnect
   public function close() {
      $this->conn = null;
   }
}