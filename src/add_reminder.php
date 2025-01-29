<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) 
{
    header("Location: login.php");  //back to login page if not logged in
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $user_id = $_SESSION['user_id'];
    $reminder_date = $_POST['date'];
    $reminder_title = trim($_POST['reminder_title']);
    $reminder_desc = trim($_POST['reminder_desc']);

    if (!empty($reminder_date) && !empty($reminder_title) && !empty($reminder_desc))
    {
    
        $sql = "INSERT INTO reminders (user_id, reminder_date, reminder_title, reminder_desc) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $user_id, $reminder_date, $reminder_title, $reminder_desc);

        if ($stmt->execute()) 
        {
            header("Location: calendar.php");  //back to calendar page after adding reminder
        } 
        else 
        {
            echo "Error adding reminder: " . $stmt->error;
        }
    } 
    else 
    {
        echo "All fields are required!";
    }
}

?>
