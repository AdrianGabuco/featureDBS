<?php
include_once("db.php");
include_once("employee.php");

$db = new Database();
$connection = $db->getConnection();
$employee = new Employee($db);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Lists</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <!-- Include the header -->
    <?php include('header.html'); ?>
    <?php include('navbar.php'); ?>

    <div class="content">
    <h2>Employee Lists</h2>
    <table class="orange-theme">
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Job Position</th>
                <th>Evaluated Status</th>
            </tr>
        </thead>
        <tbody>
            <!-- You'll need to dynamically generate these rows with data from your database -->
       
            
            
            <?php
            $results = $employee->displayAll(); 
            foreach ($results as $result) {
            ?>
            <tr>
                <td><?php echo $result['idemployees']; ?></td>
                <td><?php echo $result['employee_name']; ?></td>
                <td><?php echo $result['job_category']; ?></td>
                <td><?php echo $result['evaluation_status']; ?></td>
            </tr>
        <?php } ?>

           
        </tbody>
    </table>
        </div>
        
        <!-- Include the header -->
  



    <p></p>
</body>
</html>
