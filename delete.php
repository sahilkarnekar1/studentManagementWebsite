<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';

// Check if id is passed as a query parameter
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the SQL statement to fetch the student details
    $sql = "SELECT image FROM student WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();

    // Prepare the SQL statement to delete the student
    $sql = "DELETE FROM student WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Remove the student's image if it exists
        if (!empty($student['image']) && file_exists("uploads/" . $student['image'])) {
            unlink("uploads/" . $student['image']);
        }
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting the record: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No student ID provided.";
    exit();
}
?>
