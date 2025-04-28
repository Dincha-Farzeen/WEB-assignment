<?php
require_once("SimpleRest.php");
require_once("reviewController.php");

class RestHandler {
    function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];  //Get the HTTP method (GET, POST, etc.)
        $reqString = $_REQUEST['request'] ?? '';
        $resource  = explode('/', trim($reqString, '/'))[0] ?? '';


        if ($resource == "review") {
            $controller = new ReviewController();  //Instantiate the reviewController
            $rest = new SimpleRest();  //To manage headers...with status codes

            
            if ($method == "GET") {
                $data = $controller->getAllReviews();  
                $rest->setHttpHeaders("application/json", 200);  //Set response headers to JSON
                echo json_encode($data);  //json file procuction
            } 
            elseif ($method == "POST") { 

                //read raw POST body and put into a php associative array (JSON consumption)
                $postData = json_decode(file_get_contents('php://input'), true);

                $result = $controller->addReview($postData);
                $rest->setHttpHeaders("application/json", isset($result['error']) ? 400 : 201);
                echo json_encode($result);

            } else {
                $rest->setHttpHeaders("application/json", 405);
                echo json_encode(["error" => "Method Not Allowed"]);
            }
        } else {  
            $rest = new SimpleRest();  
            $rest->setHttpHeaders("application/json", 404);  
            echo json_encode(["error" => "Resource Not Found"]);
        }
    }
}

$handler = new RestHandler();  
$handler->handleRequest();  // Handle the incoming request

?>
