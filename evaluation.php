<?php
include_once("db.php"); // Include the Database class file

class Evaluation {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create($data) {
        try {
            // Now, insert a record in the evaluations table
            $evaluationSql = "
                INSERT INTO evaluations(employee_id, evaluator_name, evaluation_type, commitment, problem_identification, task_performance, 
                interpersonal_relationship, professional_posture, initiative, system_overview, punctuality, attendance, overall_performance,
                 evaluation_date)
                VALUES(:employee_id, :evaluator_name, :evaluation_type, :commitment, :problem_identification, :task_performance, 
                :interpersonal_relationship, :professional_posture, :initiative, :system_overview, :punctuality, :attendance, 
                :overall_performance, NOW())
            ";

            $evaluationStmt = $this->db->getConnection()->prepare($evaluationSql);

            // Bind values to placeholders
            $evaluationStmt->bindParam(':employee_id', $data['employee_id']);
            $evaluationStmt->bindParam(':evaluator_name', $data['evaluator_name']);
            $evaluationStmt->bindParam(':evaluation_type', $data['evaluation_type']);
            $evaluationStmt->bindParam(':commitment', $data['commitment']);
            $evaluationStmt->bindParam(':problem_identification', $data['problem_identification']);
            $evaluationStmt->bindParam(':task_performance', $data['task_performance']);
            $evaluationStmt->bindParam(':interpersonal_relationship', $data['interpersonal_relationship']);
            $evaluationStmt->bindParam(':professional_posture', $data['professional_posture']);
            $evaluationStmt->bindParam(':initiative', $data['initiative']);
            $evaluationStmt->bindParam(':system_overview', $data['system_overview']);
            $evaluationStmt->bindParam(':punctuality', $data['punctuality']);
            $evaluationStmt->bindParam(':attendance', $data['attendance']);
            $evaluationStmt->bindParam(':overall_performance', $data['overall_performance']);

            // Execute the INSERT query for evaluations table
            $evaluationStmt->execute();

            $this->updatePerformanceEvaluation($data['employee_id']);

            // Check if the insert was successful
            if ($evaluationStmt->rowCount() > 0) {
                return $this->db->getConnection()->lastInsertId();
            }
        } catch (PDOException $e) {
            // Handle any potential errors here
            echo "Error: " . $e->getMessage();
            throw $e; // Re-throw the exception for higher-level handling
        }
    }

    public function read($id) {
        try {
            $connection = $this->db->getConnection();

            $sql = "SELECT * FROM performance_evaluation WHERE evaluation_id = :id";
            $stmt = $connection->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            // Fetch the student data as an associative array
            $townCityData = $stmt->fetch(PDO::FETCH_ASSOC);

            return $townCityData;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            throw $e; // Re-throw the exception for higher-level handling
        }
    }

    public function update($id, $data) {
        try {
            $sql = "UPDATE performance_evaluation 
                    SET employee_id = :employee_id,
                        seniors_evaluation = :seniors_evaluation,
                        self_evaluation = :self_evaluation,
                        peer_evaluation = :peer_evaluation
                    WHERE evaluation_id = :id";
    
            $stmt = $this->db->getConnection()->prepare($sql);
            // Bind parameters
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':employee_id', $data['employee_id']);
            $stmt->bindValue(':seniors_evaluation', $data['seniors_evaluation']);
            $stmt->bindValue(':self_evaluation', $data['self_evaluation']);
            $stmt->bindValue(':peer_evaluation', $data['peer_evaluation']);
    
            // Execute the query
            $stmt->execute();
    
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            throw $e; // Re-throw the exception for higher-level handling
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM performance_evaluation WHERE evaluation_id = :id";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindValue(':id', $id);
            $success = $stmt->execute();
    
            return $success;
        } catch (PDOException $e) {
            // Log the error or handle it appropriately
            error_log("Error deleting record: " . $e->getMessage(), 0);
            throw $e; // Re-throw the exception for higher-level handling
        }
    }

    public function getAll() {
        try {
            $sql = "SELECT pe.evaluation_id, pe.employee_id, CONCAT(e.first_name, ' ', e.last_name) as employee_name, jp.job_category,
             pe.seniors_evaluation, pe.self_evaluation,
            pe.peer_evaluation,
            CASE
                WHEN pe.overall_performance >= 90 THEN 'Excellent'
                WHEN pe.overall_performance >= 80 THEN 'Regular'
                WHEN pe.overall_performance >= 70 THEN 'Good'
                WHEN pe.overall_performance >= 1 AND pe.overall_performance <=69 THEN 'Insufficient'
                ELSE NULL
            END as overall_performance, pe.evaluation_date FROM performance_evaluation as pe
            LEFT JOIN employees AS e ON pe.employee_id = e.idemployees
            LEFT JOIN service_records AS sr ON e.idemployees = sr.employees_idemployees
            LEFT JOIN job_positions AS jp ON sr.job_positions_idjob_positions = jp.idjob_positions";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle errors (log or display)
            throw $e; // Re-throw the exception for higher-level handling
        }
    }
    private function updatePerformanceEvaluation($employeeId) {
        try {
            // Check if there is an existing record for the employee in performance_evaluation
            $existingQuery = "SELECT * FROM performance_evaluation WHERE employee_id = :employee_id";
            $stmtExisting = $this->db->getConnection()->prepare($existingQuery);
            $stmtExisting->bindParam(':employee_id', $employeeId);
            $stmtExisting->execute();
    
            if ($stmtExisting->rowCount() > 0) {
                // Update the existing record in performance_evaluation
                $updateQuery = "UPDATE performance_evaluation 
                                SET seniors_evaluation = (SELECT overall_performance FROM evaluations WHERE employee_id = 
                                :employee_id AND evaluation_type = 'senior'),
                                    self_evaluation = (SELECT overall_performance FROM evaluations WHERE employee_id = 
                                    :employee_id AND evaluation_type = 'self'),
                                    peer_evaluation = (SELECT overall_performance FROM evaluations WHERE employee_id = 
                                    :employee_id AND evaluation_type = 'peer'),
                                    overall_performance = (SELECT AVG(score) FROM (
                                        SELECT overall_performance as score FROM evaluations WHERE employee_id = 
                                        :employee_id AND evaluation_type = 'senior'
                                        UNION ALL
                                        SELECT overall_performance as score FROM evaluations WHERE employee_id = 
                                        :employee_id AND evaluation_type = 'self'
                                        UNION ALL
                                        SELECT overall_performance as score FROM evaluations WHERE employee_id = 
                                        :employee_id AND evaluation_type = 'peer'
                                    ) AS subquery)
                                WHERE employee_id = :employee_id";
    
                $stmtUpdate = $this->db->getConnection()->prepare($updateQuery);
                $stmtUpdate->bindParam(':employee_id', $employeeId);
                $stmtUpdate->execute();
            } else {
                // Insert a new record in performance_evaluation
                $insertQuery = "INSERT INTO performance_evaluation (employee_id, job_position_id, seniors_evaluation, self_evaluation, 
                peer_evaluation, overall_performance)
                                VALUES (:employee_id,
                                        (SELECT jp.idjob_positions FROM job_positions as jp
                                        LEFT JOIN service_records as sr ON jp.idjob_positions = sr.job_positions_idjob_positions
                                        LEFT JOIN employees as e ON sr.employees_idemployees = e.idemployees
                                        WHERE e.idemployees = :employee_id),
                                        (SELECT overall_performance FROM evaluations WHERE employee_id = :employee_id AND 
                                        evaluation_type = 'senior'),
                                        (SELECT overall_performance FROM evaluations WHERE employee_id = :employee_id AND 
                                        evaluation_type = 'self'),
                                        (SELECT overall_performance FROM evaluations WHERE employee_id = :employee_id AND 
                                        evaluation_type = 'peer'),
                                        (SELECT AVG(score) FROM (
                                            SELECT overall_performance as score FROM evaluations WHERE employee_id = :employee_id AND 
                                            evaluation_type = 'senior'
                                            UNION ALL
                                            SELECT overall_performance as score FROM evaluations WHERE employee_id = :employee_id AND 
                                            evaluation_type = 'self'
                                            UNION ALL
                                            SELECT overall_performance as score FROM evaluations WHERE employee_id = :employee_id AND 
                                            evaluation_type = 'peer'
                                        ) AS subquery))";
    
                $stmtInsert = $this->db->getConnection()->prepare($insertQuery);
                $stmtInsert->bindParam(':employee_id', $employeeId);
                $stmtInsert->execute();
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            throw $e;
        }
    }
    
}

?>
