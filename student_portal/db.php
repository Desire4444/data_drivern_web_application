<?php

    $conn = mysqli_connect("localhost", "root", "", "student_portal");

    if(!$conn){
        die("connection failed: " . mysqli_connect_error());
    }

?>