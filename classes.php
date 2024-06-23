<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';

// Handle add class
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_class'])) {
    $class_name = $_POST['class_name'];
    $stmt = $conn->prepare("INSERT INTO classes (name) VALUES (?)");
    $stmt->bind_param("s", $class_name);
    $stmt->execute();
    $stmt->close();
    header("Location: classes.php");
    exit();
}

// Handle edit class
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_class'])) {
    $class_id = $_POST['class_id'];
    $class_name = $_POST['class_name'];
    $stmt = $conn->prepare("UPDATE classes SET name = ? WHERE class_id = ?");
    $stmt->bind_param("si", $class_name, $class_id);
    $stmt->execute();
    $stmt->close();
    header("Location: classes.php");
    exit();
}

// Handle delete class
if (isset($_GET['delete_id'])) {
    $class_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM classes WHERE class_id = ?");
    $stmt->bind_param("i", $class_id);
    $stmt->execute();
    $stmt->close();
    header("Location: classes.php");
    exit();
}

// Fetch all classes
$sql = "SELECT * FROM classes";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Classes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">Manage Classes</h1>

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Class Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="editClass(<?php echo $row['class_id']; ?>, '<?php echo htmlspecialchars(addslashes($row['name'])); ?>')">Edit</button>
                    <a href="classes.php?delete_id=<?php echo $row['class_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this class?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="form-container mt-4">
        <h2>Add New Class</h2>
        <form action="" method="POST">
            <div class="form-group">
                <input type="text" class="form-control" name="class_name" placeholder="Class Name" required>
            </div>
            <button type="submit" class="btn btn-primary" name="add_class">Add Class</button>
        </form>
    </div>

    <div class="form-container mt-4" id="edit-form-container" style="display:none;">
        <h2>Edit Class</h2>
        <form action="" method="POST">
            <input type="hidden" name="class_id" id="edit_class_id">
            <div class="form-group">
                <input type="text" class="form-control" name="class_name" id="edit_class_name" placeholder="Class Name" required>
            </div>
            <button type="submit" class="btn btn-primary" name="edit_class">Update Class</button>
        </form>
    </div>

    <a href="index.php" class="btn btn-secondary mt-3">Back to Home</a>
</div>

<script>
function editClass(id, name) {
    document.getElementById('edit_class_id').value = id;
    document.getElementById('edit_class_name').value = name;
    document.getElementById('edit-form-container').style.display = 'block';
}
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
