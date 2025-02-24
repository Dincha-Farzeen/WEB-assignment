<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Trial</title>
</head>
<body>
<?php include 'C:\xampp\htdocs\web-assignment\Trial\src\code trial.php'; ?>

    <?php foreach ($reviews as $reviews): ?>   
    <div>
        <p><?php echo htmlspecialchars($reviews['u_name']); ?> </p> 
    </div>
    <?php endforeach; ?>
</body>
</html>