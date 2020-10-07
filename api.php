<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');

// Required files
require_once('config/database.php');
require_once('classes/Course.class.php');

// Save the request method for later use
$method = $_SERVER['REQUEST_METHOD'];

// If a param of ID is set, save that too
if(isset($_GET['id'])) {
   $id = $_GET['id'] != '' ? $_GET['id'] : null;
}

// Database instance
$database = new Database();
$dbConn = $database->connect();

// Course instance
$course = new Course($dbConn);

// Depending on which request method is used, return or manipulate data
switch($method) {
   case 'GET':
      if(!isset($id)) {
         $result = $course->read();
      } else {
         $result = $course->readOne($id);
      }

      if(sizeof($result) > 0) {
         http_response_code(200); // OK
      } else {
         http_response_code(404); // Not found
         $result = array('message' => 'No courses found');
      }
      break;
   case 'POST':
      $data = json_decode(file_get_contents('php://input'));

      $course->name = $data->name;
      $course->code = $data->code;
      $course->progression = $data->progression;
      $course->syllabus = $data->syllabus;

      if($course->create()) {
         http_response_code(201); // Created
         $result = array('message' => 'Course created');
      } else {
         http_response_code(503); // Server error
         $result = array('message' => 'Something went wrong when creating course');
      }
      break;
   case 'PUT':
      if(!isset($id)) {
         http_response_code(510); // Not extended
         $result = array('message' => 'No id found');
      } else {
         $data = json_decode(file_get_contents('php://input'));

         $course->code = $data->code;
         $course->name = $data->name;
         $course->progression = $data->progression;
         $course->course_syllabus = $data->course_syllabus;

         if($course->update($id)) {
            http_response_code(200); // OK
            $result = array('message' => 'Kurs uppdaterad');
         } else {
            http_response_code(503); // Server error
            $result = array('message' => 'Something went wrong when updating course');
         }
      }
      break;
   case 'DELETE':
      if(!isset($id)) {
         http_response_code(510); // Not extended
         $result = array('message' => 'No id found');
      } else {
         if($course->delete($id)) {
            http_response_code(200); // OK
            $result = array('message' => 'Course is deleted');
         } else {
            http_response_code(503); // Server error
            $result = array('message' => 'Something went wrong when deleting course');
         }
      }
      break;
}

// Echo the result as JSON
echo json_encode($result);

// Close DB connection
$db = $database->close();