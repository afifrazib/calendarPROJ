<?php
session_start();
include 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) 
    {
        //check existing users
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) 
        {
            //fetch user data
            $user = $result->fetch_assoc();
            
            //password verification
            if (password_verify($password, $user['password'])) 
            {
                // starts the session when pass is correct
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: calendar.php");
                exit;
            } 
            else 
            {
                // back to login page if incorrect password
                header("Location: login.php?status=failed");
                exit;
            }
        } 
        else 
        {
            //username takde back to login
            header("Location: login.php?status=failed");
            exit;
        }
    } 
    else 
    {
        //kosong
        header("Location: login.php?status=failed");
        exit;
    }
}
?>
