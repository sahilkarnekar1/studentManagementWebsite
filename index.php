<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Demo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
<a href="create.php" class="btn btn-primary mt-3">Register Student</a>
    <a href="classes.php" class="btn btn-primary mt-3">Classes</a>
    <h1 class="mb-4">Student List</h1>

    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    include 'db_connect.php';

    $sql = "SELECT student.id, student.name, student.email, student.created_at, student.image, classes.name as class_name 
            FROM student 
            JOIN classes ON student.class_id = classes.class_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table class='table table-bordered table-striped'>";
        echo "<thead class='thead-dark'>";
        echo "<tr><th>Name</th><th>Email</th><th>Creation Date</th><th>Class</th><th>Image</th><th>Actions</th></tr>";
        echo "</thead><tbody>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["class_name"]) . "</td>";
            echo "<td><img src='uploads/". htmlspecialchars($row["image"]) . "' alt='Student Image' class='img-thumbnail' style='max-width: 100px; max-height: 100px;'></td>";
            echo "<td>
                    <a href='view.php?id=" . htmlspecialchars($row["id"]) . "' class='btn btn-info btn-sm'>View</a>
                    <a href='edit.php?id=" . htmlspecialchars($row["id"]) . "' class='btn btn-warning btn-sm'>Edit</a>
                    <a href='delete.php?id=" . htmlspecialchars($row["id"]) . "' class='btn btn-danger btn-sm'>Delete</a>
                  </td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No results found.</p>";
    }
    $conn->close();
    ?>
   
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
