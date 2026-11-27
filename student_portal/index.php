<?php

require_once "db.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $fullname = $_POST["fullname"] ?? "";
    $regnumber = $_POST["regnumber"] ?? "";
    $email = $_POST["email"] ?? "";
    $programme = $_POST["programme"] ?? "";
    $gender = $_POST["gender"] ?? "";
    $phone = $_POST["phone"] ?? "";
    
    $errors = [];
    if(empty($fullname)){
        $errors[] = "Full name is required";
    }
    elseif(!preg_match("/^[a-zA-Z\s'-]+$/", $fullname)){
        $errors[] = "Full name must contain letters only";
    }
    if(empty($regnumber)){
        $errors[] = "reg number is required";
    }
    elseif(!ctype_digit($regnumber)){
        $errors[] = "Registration number must contain digits only";
    }
    if(empty($email)){
        $errors[] = "Email is required";
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = "Please enter a valid email address";
    }
    if(empty($programme)){
        $errors[] = "programme is required";
    }
    elseif(!empty($programme) && !in_array($programme, ["ICT", "Nursing", "Business Studies"])){
        $errors[] = "Invalid programme selection";
    }
    if(empty($gender)){
        $errors[] = "gender is required";
    }
    elseif(!empty($gender) && !in_array($gender, ["Male", "Female"])){
        $errors[] = "Invalid gender selection";
    }
    if(empty($phone)){
        $errors[] = "phone number is required";
    }
    elseif(!empty($phone) && !preg_match('/^(?:0\d{9}|\+265\d{9}|265\d{9})$/', $phone)){
        $errors[] = "Please enter a valid Malawi phone number";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO students 
                (fullname, regnumber, email, programme, gender, phone)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "ssssss",
                $fullname,
                $regnumber,
                $email,
                $programme,
                $gender,
                $phone
            );

            if (mysqli_stmt_execute($stmt)) {
                $success = "Student registered successfully!";
            } else {
                $errors[] = "Unable to register student.";
            }

            mysqli_stmt_close($stmt);

        } else {
            $errors[] = "Unable to prepare registration.";
        }
    }
}

$sql = "SELECT * FROM students";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>
    <head>
    <title>Student Registration</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    
    <h1>Student Registration Form</h1>

    <h2>Registered Students</h2>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Registration Number</th>
            <th>Email</th>
            <th>Programme</th>
            <th>Gender</th>
            <th>Phone</th>
        </tr>

        <?php while ($student = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($student["student_id"]); ?></td>
                <td><?php echo htmlspecialchars($student["fullname"]); ?></td>
                <td><?php echo htmlspecialchars($student["regnumber"]); ?></td>
                <td><?php echo htmlspecialchars($student["email"]); ?></td>
                <td><?php echo htmlspecialchars($student["programme"]); ?></td>
                <td><?php echo htmlspecialchars($student["gender"]); ?></td>
                <td><?php echo htmlspecialchars($student["phone"]); ?></td>
                <td>
                   <a href="edit.php?id=<?php echo (int)$student['student_id']; ?>">
                        Edit
                    </a>

                    <form action="delete.php" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this student?');">
                        <input type="hidden" name="id"
                            value="<?php echo (int)$student['student_id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>

    </table>
    
    <?php if (!empty($errors)): ?>
    
        <h3>Please correct the following errors:</h3>
    
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    
    <?php endif; ?>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($errors)): ?>
        <h2><?php echo htmlspecialchars($success); ?></h2>
        <h3>Submitted Student Information</h3>

        <p>Full Name: <?php echo htmlspecialchars($fullname); ?></p>
        <p>Registration Number: <?php echo htmlspecialchars($regnumber); ?></p>
        <p>Email: <?php echo htmlspecialchars($email); ?></p>
        <p>Programme: <?php echo htmlspecialchars($programme); ?></p>
        <p>Gender: <?php echo htmlspecialchars($gender); ?></p>
        <p>Phone Number: <?php echo htmlspecialchars($phone); ?></p>
    <?php endif; ?>

    <h2>Registration</h2>
    
    <form method="POST" class='form'>
        <!-- <div></div> -->
        <!-- FullName -->
        <label for="fullname">Full Name:</label>
        <input type="text" id="fullname" name="fullname">
        <br><br>
        <!-- RegNumber -->
        <label for="regnumber">Registration Number:</label>
        <input type="text" id="regnumber" name="regnumber">
        <br><br>
        <!-- Email -->
        <label for="email">Email:</label>
        <input type="email" id="email" name="email">
        <br><br>
        <!-- Programme -->
        <label for="programme">Programme:</label>
        <select id="programme" name="programme">
            <option value="">Select Programme</option>
            <option value="ICT">ICT</option>
            <option value="Nursing">Nursing</option>
            <option value="Business Studies">Business Studies</option>
        </select>
        <br><br>
        <!-- Gender -->
        <label>Gender:</label>
        <input type="radio" id="male" name="gender" value="Male">
        <label for="male">Male</label>
        <input type="radio" id="female" name="gender" value="Female">
        <label for="female">Female</label>
        <br><br>
        <!-- PhoneNumber -->
        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone">
        <br><br>
        <!-- RegisterButton -->
        <button type="submit">Register</button>

    </form>


</body>
</html>