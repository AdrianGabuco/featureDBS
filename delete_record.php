<?php
include_once("db.php");
include_once("evaluation.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];

    $db = new Database();
    $evaluation = new Evaluation($db);

    // Call the delete method to delete the evaluation record
    if ($evaluation->delete($id)) {
        echo "Record deleted successfully.";
    } else {
        echo "Failed to delete the record.";
    }
}
?>