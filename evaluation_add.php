<?php
include_once("db.php"); // Include the Database class file
include_once("evaluation.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [    
    'employee_id' => $_POST['employee_id'],
    'evaluator_name' => $_POST['evaluator_name'],
    'evaluation_type' => $_POST['evaluation_type'],
    'commitment' => $_POST['commitment'],
    'problem_identification' => $_POST['problem_identification'],
    'task_performance' => $_POST['task_performance'],
    'interpersonal_relationship' => $_POST['interpersonal_relationship'],
    'professional_posture' => $_POST['professional_posture'],
    'initiative' => $_POST['initiative'],
    'system_overview' => $_POST['system_overview'],
    'punctuality' => $_POST['punctuality'],
    'attendance' => $_POST['attendance'],
    'overall_performance' => $_POST['overall_performance'],

    ];

    // Instantiate the Database and town/city classes
    $database = new Database();
    $evaluation = new Evaluation($database);
        
    if ($evaluation->create($data)){
        echo "Record inserted successfully.";
    } else {
        echo "Failed to insert Record.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">

    <title>Add New Evaluation</title>
</head>
<body>
    <!-- Include the header and navbar -->
    <?php include('header.html'); ?>
    <?php include('navbar.php'); ?>

    <div class="content">
    <h1>Add New Evaluation</h1>
    <form action="" method="post" class="form-label">
        <label for="employee_id">Select Employee:</label>
        <select name="employee_id" required>
        <!-- Fetch employee names and IDs from the database and populate the dropdown -->
        <?php
         //Replace this with your database connection logic
        $db = new PDO("mysql:host=localhost;dbname=mydb", "root", "captainbuko");
        $database = new Database();
        $townCity = new Evaluation($database);

        $sql = "SELECT idemployees, CONCAT(first_name, ' ', last_name) as employee_name FROM employees";
        $stmt = $db->query($sql);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='{$row['idemployees']}'>{$row['employee_name']}</option>";
        }
        ?>
    </select>

    <label for="evaluator_name">Evaluator Name:</label>
    <input type="text" name="evaluator_name" required>

    <label for="evaluation_type">Evaluation Type:</label>
    <select name="evaluation_type" required>
        <option value="senior">Senior</option>
        <option value="self">Self</option>
        <option value="peer">Peer</option>
    </select>

    <label for="commitment">Commitment:</label>
    <input type="number" name="commitment" required>

    <label for="problem_identification">Problem Identification:</label>
    <input type="number" name="problem_identification" required>

    <label for="task_performance">Task Performance:</label>
    <input type="number" name="task_performance" required>

    <label for="interpersonal_relationship">Interpersonal Relationship:</label>
    <input type="number" name="interpersonal_relationship" required>

    <label for="professional_posture">Professional Posture:</label>
    <input type="number" name="professional_posture" required>

    <label for="initiative">Initiative:</label>
    <input type="number" name="initiative" required>

    <label for="system_overview">System Overview:</label>
    <input type="number" name="system_overview" required>

    <label for="punctuality">Punctuality:</label>
    <input type="number" name="punctuality" required>

    <label for="attendance">Attendance:</label>
    <input type="number" name="attendance" required>

    <label for="overall_performance">Overall Performance:</label>
    <input type="text" name="overall_performance" readonly>

    <!-- Add input fields for other evaluation components -->

    <input type="submit" value="Submit Evaluation">
</form>

<script>
    // JavaScript to calculate and update the overall performance score
    function calculateOverallPerformance() {
        // Get the values of each component
        var commitment = parseFloat(document.getElementsByName("commitment")[0].value);
        var problemIdentification = parseFloat(document.getElementsByName("problem_identification")[0].value);
        var taskPerformance = parseFloat(document.getElementsByName("task_performance")[0].value);
        var interpersonalRelationship = parseFloat(document.getElementsByName("interpersonal_relationship")[0].value);
        var professionalPosture = parseFloat(document.getElementsByName("professional_posture")[0].value);
        var initiative = parseFloat(document.getElementsByName("initiative")[0].value);
        var systemOverview = parseFloat(document.getElementsByName("system_overview")[0].value);
        var punctuality = parseFloat(document.getElementsByName("punctuality")[0].value);
        var attendance = parseFloat(document.getElementsByName("attendance")[0].value);

        // Calculate the overall performance score (average of all components)
        var overallPerformance = (commitment + problemIdentification + taskPerformance +
            interpersonalRelationship + professionalPosture + initiative + systemOverview +
            punctuality + attendance) / 9;

        // Update the readonly field with the calculated overall score
        document.getElementsByName("overall_performance")[0].value = overallPerformance.toFixed(2);
    }

    // Attach the function to the input fields' onchange event
    var inputFields = document.querySelectorAll('input[type="number"]');
    inputFields.forEach(function (field) {
        field.addEventListener('input', calculateOverallPerformance);
    });
</script>

</body>
</html>