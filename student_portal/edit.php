<?php

require_once "db.php";

$id = $_GET["id"] ?? "";

$sql = "SELECT * FROM students WHERE student_id = ?";
$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$student = mysqli_fetch_assoc($result);

if (!$student) {
    die("Student not found.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = $_POST["fullname"] ?? "";
    $regnumber = $_POST["regnumber"] ?? "";
    $email = $_POST["email"] ?? "";
    $programme = $_POST["programme"] ?? "";
    $gender = $_POST["gender"] ?? "";
    $phone = $_POST["phone"] ?? "";

    $sql = "UPDATE students SET
            fullname = ?,
            regnumber = ?,
            email = ?,
            programme = ?,
            gender = ?,
            phone = ?
            WHERE student_id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssi",
        $fullname,
        $regnumber,
        $email,
        $programme,
        $gender,
        $phone,
        $id
    );

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Unable to update student information.";
    }

    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>Edit Student Information</h1>

    <form method="POST">

        <label>Full Name:</label>
        <input type="text" name="fullname"
        value="<?php echo htmlspecialchars($student['fullname']); ?>">
        <br><br>

        <label>Registration Number:</label>
        <input type="text" name="regnumber"
        value="<?php echo htmlspecialchars($student['regnumber']); ?>">
        <br><br>

        <label>Email:</label>
        <input type="email" name="email"
        value="<?php echo htmlspecialchars($student['email']); ?>">
        <br><br>

        <label>Programme:</label>
        <select name="programme">
            <option value="ICT" <?php if($student['programme'] == "ICT") echo "selected"; ?>>ICT</option>
            <option value="Nursing" <?php if($student['programme'] == "Nursing") echo "selected"; ?>>Nursing</option>
            <option value="Business Studies" <?php if($student['programme'] == "Business Studies") echo "selected"; ?>>Business Studies</option>
        </select>
        <br><br>

        <label>Gender:</label>
        <input type="radio" name="gender" value="Male"
        <?php if($student['gender'] == "Male") echo "checked"; ?>> Male

        <input type="radio" name="gender" value="Female"
        <?php if($student['gender'] == "Female") echo "checked"; ?>> Female
        <br><br>

        <label>Phone:</label>
        <input type="tel" name="phone"
        value="<?php echo htmlspecialchars($student['phone']); ?>">
        <br><br>

        <button type="submit">Update Student</button>

    </form>

</body>
</html>