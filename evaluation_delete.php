<?php
include_once("db.php"); // Include the Database class file
include_once("evaluation.php"); // Include the Evaluation class file

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id']; // Retrieve the 'id' from the URL
    $confirmationMessage = "Are you sure you want to delete this record?";

    // JavaScript confirmation dialog
    echo "<script>
            var confirmDelete = confirm('$confirmationMessage');
            if (confirmDelete) {
                window.location.href = 'delete_record.php?id=$id';
            } else {
                window.location.href = 'index.php'; // Redirect to the main page or wherever you want
            }
          </script>";
}
?>