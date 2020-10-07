<?php
class Course {
   // Connection
   private $conn;

   // DB table
   private $table = 'courses';

   // DB columns
   public $id;
   public $name;
   public $code;
   public $progression;
   public $course_syllabus;

   // Constructor 
   public function __construct($dbConn) {
      $this->conn = $dbConn;
   }

   // READ
   public function read() {
      $query = 'SELECT * FROM ' . $this->table;
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $num = $stmt->rowCount();

      $data = array();

      if ($num > 0) {
         $data['data'] = array();
         $data['itemCount'] = $num;

         while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row); // extract data from row as variables

            $course = array(
               'id' => $id,
               'code' => $code,
               'name' => $name,
               'progression' => $progression,
               'course_syllabus' => $course_syllabus
            );

            array_push($data['data'], $course);
         }
      }

      return $data;
   }

   // READ ONE
   public function readOne($id) {
      $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ' . $id . ' LIMIT 1';
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $data = $stmt->fetch(PDO::FETCH_ASSOC);

      if(!$data) {
         $data = array();
      }

      return $data;

   }

   // CREATE
   public function create() {
      $query = 'INSERT INTO 
                  ' . $this->table . '
               SET
                  code = :code,
                  name = :name,
                  progression = :progression,
                  course_syllabus = :course_syllabus';
      $stmt = $this->conn->prepare($query);
      
      // Sanitize input
      $this->code = htmlspecialchars(strip_tags($this->code));
      $this->name = htmlspecialchars(strip_tags($this->name));
      $this->progression = htmlspecialchars(strip_tags($this->progression));
      $this->course_syllabus = htmlspecialchars(strip_tags($this->course_syllabus));

      // Bind data to params
      $stmt->bindParam(':code', $this->code);
      $stmt->bindParam(':name', $this->name);
      $stmt->bindParam(':progression', $this->progression);
      $stmt->bindParam(':course_syllabus', $this->course_syllabus);

      if($stmt->execute()) {
         return true;
      }

      return false;
   }
   
   // UPDATE
   public function update($id) {
      $query = 'UPDATE 
                  ' . $this->table . '
               SET
                  code = :code,
                  name = :name,
                  progression = :progression,
                  course_syllabus = :course_syllabus
               WHERE
                  id = :id';
      $stmt = $this->conn->prepare($query);

      // Sanitize input
      $this->id = htmlspecialchars(strip_tags($id));
      $this->code = htmlspecialchars(strip_tags($this->code));
      $this->name = htmlspecialchars(strip_tags($this->name));
      $this->progression = htmlspecialchars(strip_tags($this->progression));
      $this->course_syllabus = htmlspecialchars(strip_tags($this->course_syllabus));

      // Bind data to params
      $stmt->bindParam(':id', $this->id);
      $stmt->bindParam(':code', $this->code);
      $stmt->bindParam(':name', $this->name);
      $stmt->bindParam(':progression', $this->progression);
      $stmt->bindParam(':course_syllabus', $this->course_syllabus);

      if($stmt->execute()) {
         return true;
      }

      return false;
   }

   // DELETE
   public function delete($id) {
      $query = 'DELETE FROM
                  ' . $this->table . '
               WHERE
                  id = :id';
      $stmt = $this->conn->prepare($query);

      // Sanitize input
      $this->id = htmlspecialchars(strip_tags($id));

      // Bind data to params
      $stmt->bindParam(':id', $this->id);

      if($stmt->execute()) {
         return true;
      }

      return false;
   }
}
