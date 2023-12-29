<?php
include_once("db.php");
include_once("employee.php");

$db = new Database();
$connection = $db->getConnection();
$employee = new Employee($db);

// Fetch and display brief information about employee performance evaluation
$evaluationSummary = "Welcome to our Employee Performance Evaluation System. Track and manage employee evaluations efficiently. Evaluate commitment, problem identification, task performance, interpersonal relationships, professional posture, initiative, system overview, punctuality, and attendance.";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Performance Evaluation</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <!-- Include the header -->
    <?php include('header.html'); ?>
    <?php include('navbar.php'); ?>

    <div class="index1">
        <!--<h1>Employee Performance Evaluation System</h1>-->
        <h1>Welcome to our <strong>Employee Performance Evaluation System</strong></h1>
    </div>

    <div class="index2">
        <p><em>Track and manage employee evaluations efficiently. Evaluate commitment, problem identification, task performance,
             <br>interpersonal relationships, professional posture, initiative, system overview, punctuality, and attendance.</em></p>
    </div>
    <!-- Include the footer -->
    <?php //include('footer.html'); ?>
</body>
</html>