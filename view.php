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
    $sql = "SELECT student.id, student.name, student.email, student.created_at, student.image, student.address, classes.name as class_name 
            FROM student 
            JOIN classes ON student.class_id = classes.class_id
            WHERE student.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a student with the given ID exists
    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
    } else {
        echo "No student found with the given ID.";
        exit();
    }

    $stmt->close();
} else {
    echo "No student ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">View Student</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($student['name']); ?></h5>
            <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($student['email']); ?></h6>
            <p class="card-text">
                <strong>Address:</strong> <?php echo htmlspecialchars($student['address']); ?><br>
                <strong>Class:</strong> <?php echo htmlspecialchars($student['class_name']); ?><br>
                <strong>Created At:</strong> <?php echo htmlspecialchars($student['created_at']); ?>
            </p>
            <?php if (!empty($student['image'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($student['image']); ?>" alt="Student Image" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
            <?php endif; ?>
        </div>
    </div>

    <a href="index.php" class="btn btn-secondary mt-3">Back to Home</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
