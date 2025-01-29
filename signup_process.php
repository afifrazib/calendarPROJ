<?php
include 'db.php';
include 'signup.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    
    if (!empty($username) && !empty($email) && !empty($password)) 
    {
        //to check existing usernames
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) 
        {
            //username taken
            header("Location: signup.php?status=username_taken");
            exit;
        }

        // to check existing emails
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
       
        if ($result->num_rows > 0) 
        {
            //email taken
            header("Location: signup.php?status=email_taken");
            exit;
        }
        
        $hashpass = password_hash($password, PASSWORD_BCRYPT);


        //insert new user to database
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashpass);



        if ($stmt->execute()) 
        {
            //success
            header("Location: signup.php?status=success");
            exit;
        } 
        else 
        {
            //fail
            header("Location: signup.php?status=failed");
            exit;
        }
    } 
    else 
    {
        //kosong
        header("Location: signup.php?status=empty_fields");
        exit;
    }
}
?>
