<?php
//Include the database configuration file
include_once("../dataConnect.php");

//Check whether the form submitted
if($_SERVER["REQUEST_METHOD"] === "POST"){
    //Collect form data
    $c_name = $_POST["c_name"];
    $c_email = $_POST["c_email"];
    $c_phone = $_POST["c_phone"];
    $c_subject = $_POST["c_subject"];
    $c_message = $_POST["c_message"];

    // Check if the fields are empty or not
    if(empty($c_name) || empty($c_email) || empty($c_phone) || empty($c_subject)){
        // At least one field is empty
        echo "Please fill out all fields.";
    } else {
        // Perform validation for email and phone number
        if(!filter_var($c_email, FILTER_VALIDATE_EMAIL)){
            echo "Invalid email format.";
        } else if(!preg_match("/^01[0-9]{1}-[0-9]{3,4}-?[0-9]{4}$/", $c_phone)){
            echo "Invalid phone number";
        } else {
            // Insert data into the 'contact' table
            $sql = "INSERT INTO `contact` (contact_name, contact_email, contact_phone, contact_subject, contact_message) VALUES (?, ?, ?, ?, ?);";

            if($stmt = $conn->prepare($sql)){
                $stmt->bind_param("sssss", $c_name, $c_email, $c_phone, $c_subject, $c_message);

                if ($stmt->execute()) {
                    echo "Submitted successfully.";
                } else {
                    echo "Something wrong";
                }
            }else{
                echo "Something wrong";
            }
            $stmt->close();
        }
    }
}

    // Close the database connection
    $conn->close();