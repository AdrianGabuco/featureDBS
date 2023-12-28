<?php
include_once("db.php"); // Include the file with the Database class

class Employee {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // public function create($data) {
    //     try {
    //         // Prepare the SQL INSERT statement
    //         $sql = "INSERT INTO students(student_number, first_name, middle_name, last_name, gender, birthday) VALUES(:student_number, :first_name, :middle_name, :last_name, :gender, :birthday);";
    //         $stmt = $this->db->getConnection()->prepare($sql);

    //         // Bind values to placeholders
    //         $stmt->bindParam(':student_number', $data['student_number']);
    //         $stmt->bindParam(':first_name', $data['first_name']);
    //         $stmt->bindParam(':middle_name', $data['middle_name']);
    //         $stmt->bindParam(':last_name', $data['last_name']);
    //         $stmt->bindParam(':gender', $data['gender']);
    //         $stmt->bindParam(':birthday', $data['birthday']);

    //         // Execute the INSERT query
    //         $stmt->execute();

    //         // Check if the insert was successful
             
    //         if($stmt->rowCount() > 0)
    //         {
    //             return $this->db->getConnection()->lastInsertId();
    //         }

    //     } catch (PDOException $e) {
    //         // Handle any potential errors here
    //         echo "Error: " . $e->getMessage();
    //         throw $e; // Re-throw the exception for higher-level handling
    //     }
    // }

    public function read($id) {
        try {
            $connection = $this->db->getConnection();

            $sql = "SELECT * FROM employees WHERE idemployees = :idemployees";
            $stmt = $connection->prepare($sql);
            $stmt->bindValue(':idemployees', $id);
            $stmt->execute();

            // Fetch the student data as an associative array
            $studentData = $stmt->fetch(PDO::FETCH_ASSOC);

            return $studentData;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            throw $e;
        }
    }

    public function update($id, $data) {
        try {
            $sql = "UPDATE students SET
                    student_number = :student_number,
                    first_name = :first_name,
                    middle_name = :middle_name,
                    last_name = :last_name,
                    gender = :gender,
                    birthday = :birthday
                    WHERE id = :id";

            $stmt = $this->db->getConnection()->prepare($sql);
            // Bind parameters
            $stmt->bindValue(':id', $data['id']);
            $stmt->bindValue(':student_number', $data['student_number']);
            $stmt->bindValue(':first_name', $data['first_name']);
            $stmt->bindValue(':middle_name', $data['middle_name']);
            $stmt->bindValue(':last_name', $data['last_name']);
            $stmt->bindValue(':gender', $data['gender']);
            $stmt->bindValue(':birthday', $data['birthday']);

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
            $sql = "DELETE FROM students WHERE id = :id";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            // Check if any rows were affected (record deleted)
            if ($stmt->rowCount() > 0) {
                return true; // Record deleted successfully
            } else {
                return false; // No records were deleted (student_id not found)
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            throw $e; // Re-throw the exception for higher-level handling
        }
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

    /*
        sample simple tests
    */
//     public function testCreateStudent() {
//         $data = [
//             'student_number' => 'S12345',
//             'first_name' => 'John',
//             'middle_name' => 'Doe',
//             'last_name' => 'Smith',
//             'gender' => '1',
//             'birthday' => '1990-01-15',
//         ];

//         $student_id = $this->create($data);

//         if ($student_id !== null) {
//             echo "Test passed. Student created with ID: " . $student_id . PHP_EOL;
//             return $student_id;
//         } else {
//             echo "Test failed. Student creation unsuccessful." . PHP_EOL;
//         }
//     }

//     public function testReadStudent($id) {
//         $studentData = $this->read($id);

//         if ($studentData !== false) {
//             echo "Test passed. Student data read successfully: " . PHP_EOL;
//             print_r($studentData);
//         } else {
//             echo "Test failed. Unable to read student data." . PHP_EOL;
//         }
//     }

//     public function testUpdateStudent($id, $data) {
//         $success = $this->update($id, $data);

//         if ($success) {
//             echo "Test passed. Student data updated successfully." . PHP_EOL;
//         } else {
//             echo "Test failed. Unable to update student data." . PHP_EOL;
//         }
//     }

//     public function testDeleteStudent($id) {
//         $deleted = $this->delete($id);

//         if ($deleted) {
//             echo "Test passed. Student data deleted successfully." . PHP_EOL;
//         } else {
//             echo "Test failed. Unable to delete student data." . PHP_EOL;
//         }
//     }
}


// $student = new Student(new Database());

// // Test the create method
// $student_id = $student->testCreateStudent();

// // Test the read method with the created student ID
// $student->testReadStudent($student_id);

// // Test the update method with the created student ID and updated data
// $update_data = [
//     'id' => $student_id,
//     'student_number' => 'S67890',
//     'first_name' => 'Alice',
//     'middle_name' => 'Jane',
//     'last_name' => 'Doe',
//     'gender' => '0',
//     'birthday' => '1995-05-20',
// ];
// $student->testUpdateStudent($student_id, $update_data);

// // Test the delete method with the created student ID
//$student->testDeleteStudent($student_id);

?>