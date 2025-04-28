<?php
// This class is used to set HTTP headers and status codes for RESTful responses
class SimpleRest {

    // Set the HTTP response headers based on content type and status code
    public function setHttpHeaders($contentType, $statusCode) {
        // Get the appropriate status message based on the status code (like "OK", "Not Found", etc.)
        $statusMessage = $this->getHttpStatusMessage($statusCode);

        // Set the full HTTP status line (e.g., HTTP/1.1 200 OK)
        header("HTTP/1.1 $statusCode $statusMessage");

        // Set the content type header (usually application/json)
        header("Content-Type:" . $contentType);
    }

    
    // Internal method to get the status message for a given HTTP status code
    private function getHttpStatusMessage($statusCode) {
        // A list of common HTTP status codes and their messages
        $httpStatus = array(
            200 => 'OK',
            201 => 'Created',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error'
        );
          // Return the message corresponding to the status code
        // If the code is not found, default to "Internal Server Error"
        return $httpStatus[$statusCode] ?? $httpStatus[500];
    }
}
?>
