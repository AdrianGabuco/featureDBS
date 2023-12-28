<?php
include_once("db.php");
include_once("evaluation.php");

$db = new Database();
$connection = $db->getConnection();
$evaluation = new Evaluation($db);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Performance</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <!-- Include the header -->
    <?php include('header.html'); ?>
    <?php include('navbar.php'); ?>

    <div class="content">
    <h2>Employee Performance</h2>
    <table class="orange-theme">
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Job Position</th>
                <th>Senior Evaluation</th>
                <th>Self Evaluation</th>
                <th>Peer Evaluation</th>
                <th>Overall Performance</th>
                <th>Evaluation Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- You'll need to dynamically generate these rows with data from your database -->
       
            <?php
            $results = $evaluation->getAll(); 
            foreach ($results as $result) {
            ?>
            <tr>
                <td><?php echo $result['employee_id']; ?></td>
                <td><?php echo $result['employee_name']; ?></td>
                <td><?php echo $result['job_category']; ?></td>
                <td><?php echo $result['seniors_evaluation']; ?></td>
                <td><?php echo $result['self_evaluation']; ?></td>
                <td><?php echo $result['peer_evaluation']; ?></td>
                <td><?php echo $result['overall_performance']; ?></td>
                <td><?php echo $result['evaluation_date']; ?></td>
                <td>
                    <a href="evaluation_edit.php?id=<?php echo $result['evaluation_id']; ?>">Edit</a>
                    |
                    <a href="evaluation_delete.php?id=<?php echo $result['evaluation_id']; ?>">Delete</a>
                </td>
            </tr>
        <?php } ?>

           
        </tbody>
    </table>
        
    <a class="button-link" href="evaluation_add.php">Add New Evaluation</a>

        </div>
        
        <!-- Include the header -->
  



    <p></p>
</body>
</html>
