<?php
include_once("db.php"); // Include the file with the Database class

class Employee {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function displayAll(){
        try {
            $sql = "
                SELECT e.idemployees, CONCAT(e.first_name, ' ', e.last_name) AS employee_name , jp.job_category, 
                CASE WHEN pe.employee_id = e.idemployees THEN 'Evaluated' ELSE 'Not Evaluated' END AS evaluation_status
                FROM employees AS e
                LEFT JOIN performance_evaluation AS pe ON e.idemployees = pe.employee_id
                LEFT JOIN service_records AS sr ON e.idemployees = sr.employees_idemployees
                LEFT JOIN job_positions AS jp ON sr.job_positions_idjob_positions = jp.idjob_positions
            ";

            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

}

?>