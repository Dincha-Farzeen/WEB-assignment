<?php
require_once 'reviewController.php'; 
require_once 'restHandler.php'; 
session_start();

  //check if user is logged in
        if (!isset($_SESSION['u_id'])) {
            echo json_encode(['success' => false, 'message' => 'User not logged in.']);
            exit;
        }

        $postData = $_POST; 


        $postData['u_id'] = $_SESSION['u_id'];
    

$handler = new RestHandler();
$handler->handleRequest();  // <-- let handler do everything
?>


?>
