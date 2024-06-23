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
    $sql = "SELECT * FROM student WHERE id = ?";
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

// Update student details on form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $class_id = $_POST['class_id'];
    $filename = $student['image']; // Default to existing image

    // Check if a new image is uploaded
    if ($_FILES['image']['error'] == 0) {
        $tempname = $_FILES["image"]["tmp_name"];
        $filetype = $_FILES["image"]["type"];
        $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $target_dir = "uploads/";
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($tempname, $target_file)) {
            // Remove old image if new one is uploaded
            if (!empty($student['image']) && file_exists("uploads/" . $student['image'])) {
                unlink("uploads/" . $student['image']);
            }
        } else {
            echo "Error uploading the file.";
            exit();
        }
    }

    // Prepare the SQL statement to update the student details
    $sql = "UPDATE student SET name = ?, email = ?, address = ?, class_id = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $name, $email, $address, $class_id, $filename, $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating the record: " . $stmt->error;
    }

    $stmt->close();
}

$sql_classes = "SELECT * FROM classes";
$result_classes = $conn->query($sql_classes);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">Edit Student</h1>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <textarea class="form-control" id="address" name="address" required><?php echo htmlspecialchars($student['address']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="class_id">Class:</label>
            <select class="form-control" id="class_id" name="class_id" required>
                <?php while($row = $result_classes->fetch_assoc()): ?>
                    <option value="<?php echo $row['class_id']; ?>" <?php if ($row['class_id'] == $student['class_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($row['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="image">Profile Image:</label>
            <input type="file" class="form-control-file" id="image" name="image">
            <?php if (!empty($student['image'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($student['image']); ?>" alt="Student Image" class="img-thumbnail mt-2" style="max-width: 200px; max-height: 200px;">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Update Student</button>
    </form>

    <a href="index.php" class="btn btn-secondary mt-3">Back to Home</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
