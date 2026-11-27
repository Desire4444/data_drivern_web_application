**AI Usage Declaration**

I used AI tools occasionally to clarify certain programming concepts and assist with understanding errors during development. However, the implementation, testing and final adjustments were carried out by me. I take responsibility for the submitted work.

# QUESTION 5
## Server-side validation
Server-side validation is the process of checking information submitted by a user on the server before processing or saving it.

```
$fullname = $_POST["fullname"] ?? "";
$email = $_POST["email"] ?? "";
$phone = $_POST["phone"] ?? "";

$errors = [];

if (empty($fullname)) {
    $errors[] = "Full name is required.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}

if (!preg_match('/^(?:0\d{9}|\+265\d{9}|265\d{9})$/', $phone)) {
    $errors[] = "Please enter a valid phone number.";
}
```

## Prepared statements to prevent SQL injection
SQL injection is an attack where someone inserts harmful SQL commands into input fields to manipulate or access a database.

Prepared statements help prevent this by separating SQL commands from user-provided information.

```
$sql = "INSERT INTO students
        (fullname, regnumber, email, programme, gender, phone)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

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

mysqli_stmt_execute($stmt);
```

## Password hashing
Password hashing is a security technique that converts a password into a protected hash before storing it in a database.

Instead of storing passwords as plain text, PHP provides password_hash() to hash passwords.

```
$password = "student123";

$hashedPassword = password_hash(
    $password,
    PASSWORD_DEFAULT
);

echo $hashedPassword;
```

Passwords should never be stored as plain text.

Note: Our current student registration system does not collect or store passwords

## PHP sessions and authentication
A PHP session is used to store information about a user while they navigate between different pages of a website.

Sessions can help maintain authentication by remembering whether a user has successfully logged in.

### Example: Starting a session after successful login
```
<?php

session_start();

$_SESSION["username"] = "admin";

$_SESSION["logged_in"] = true;

echo "Welcome, " . $_SESSION["username"];

?>
```

### Example: Protecting a page
```
<?php

session_start();

if (!isset($_SESSION["logged_in"])) {
    header("Location: login.php");
    exit;
}

echo "Welcome to the student dashboard.";

?>
```
After logging out, the session can be destroyed

