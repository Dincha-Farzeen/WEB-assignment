
<?php

$dsn = "mysql:host=localhost;dbname=photography_collective";
$username = 'root'; 
$password = ''; 

try {
$conn = new PDO($dsn, $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

if (isset($_POST['submit'])) {
    
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $username = trim($_POST['username']);
    $password1 = trim($_POST['password']);
    $password2 = trim($_POST['password2']);
    $errors = [];  

    // Validate phone 
    if (!is_numeric($phone)) {
        $errors[] = "Phone number must be numeric.";
    }

    //double entry for password
    if ($password1 != $password2){
        $errors[] = "Password not matching.";
    }
    
    //validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = "Invalid e-mail format.";
    }

   
    if (empty($errors)) {
        $hashed_password = password_hash($password1, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO registered_user(u_name, u_email, user_name, pass_word, u_phoneNum) 
        VALUES (?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {

            if (($stmt->execute([$name, $email, $username, $hashed_password, $phone]))) { 
                header("Location: login.php");
                exit();
            } else {
                echo "Error: " . implode(", ", $stmt->errorInfo());
            }

            unset($stmt);

        } else {
            echo "Error preparing the statement: " . implode(", ", $conn->errorInfo());
        }
    } else {
        
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    }
} 
?>
