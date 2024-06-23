<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $class_id = $_POST['class_id'];
    $filename = '';

    // Handle image upload
    if ($_FILES['image']['error'] == 0) {
        $tempname = $_FILES["image"]["tmp_name"];
        $filetype = $_FILES["image"]["type"];
        $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $target_dir = "uploads/";
        $target_file = $target_dir . $filename;

        if (!move_uploaded_file($tempname, $target_file)) {
            echo "Error uploading the file.";
            exit();
        }
    }

    // Prepare the SQL statement to insert the new student
    $sql = "INSERT INTO student (name, email, address, class_id, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssis", $name, $email, $address, $class_id, $filename);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error inserting the record: " . $stmt->error;
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
    <title>Create Student</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">Create New Student</h1>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <textarea class="form-control" id="address" name="address" required></textarea>
        </div>
        <div class="form-group">
            <label for="class_id">Class:</label>
            <select class="form-control" id="class_id" name="class_id" required>
                <?php while($row = $result_classes->fetch_assoc()): ?>
                    <option value="<?php echo $row['class_id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="image">Profile Image:</label>
            <input type="file" class="form-control-file" id="image" name="image">
        </div>
        <button type="submit" class="btn btn-primary">Create Student</button>
    </form>

    <a href="index.php" class="btn btn-secondary mt-3">Back to Home</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
