<?php
include_once("db.php"); // Include the Database class file
include_once("evaluation.php"); // Include the Evaluation class file

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch evaluation data by ID from the database
    $db = new Database();
    $evaluation = new Evaluation($db);
    $evaluationData = $evaluation->read($id); // Implement the read method in the Evaluation class

    if ($evaluationData) {
        // The evaluation data is retrieved, and you can pre-fill the edit form with this data.
    } else {
        echo "Record not found.";
    }
} else {
    echo "Evaluation ID not provided.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [
        'employee_id' => $_POST['employee_id'],
        'seniors_evaluation' => $_POST['seniors_evaluation'],
        'self_evaluation' => $_POST['self_evaluation'],
        'peer_evaluation' => $_POST['peer_evaluation'],
        // Add other fields as needed
    ];

    $db = new Database();
    $evaluation = new Evaluation($db);

    // Call the update method to update the evaluation data
    if ($evaluation->update($id, $data)) {
        echo "Record updated successfully.";
    } else {
        echo "Failed to update the record.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Edit Evaluation Record</title>
</head>
<body>
    <!-- Include the header and navbar -->
    <?php include('header.html'); ?>
    <?php include('navbar.php'); ?>

    <div class="content">
    <h2>Edit Evaluation Record</h2>
    <form action="" method="post">
        <input type="hidden" name="employee_id" value="<?php echo $evaluationData['employee_id']; ?>">
        
        <label for="seniors_evaluation">Senior Evaluation:</label>
        <input type="text" name="seniors_evaluation" id="seniors_evaluation" value="<?php echo $evaluationData['seniors_evaluation']; ?>">
        
        <label for="self_evaluation">Self Evaluation:</label>
        <input type="text" name="self_evaluation" id="self_evaluation" value="<?php echo $evaluationData['self_evaluation']; ?>">

        <label for="peer_evaluation">Peer Evaluation:</label>
        <input type="text" name="peer_evaluation" id="peer_evaluation" value="<?php echo $evaluationData['peer_evaluation']; ?>">
        
        <input type="submit" value="Update">
    </form>
    </div>
    
</body>
</html>
