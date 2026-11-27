
<?php

require_once "db.php";

$id = $_POST["id"] ?? "";

$sql = "DELETE FROM students WHERE student_id = ?";
$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: index.php");
    exit;
} else {
    echo "Unable to delete student.";
}

mysqli_stmt_close($stmt);

?>
