<?php
session_start();
if (!isset($_SESSION['user_id'])) 
{
    header("Location: login.php");
    exit;
}

include 'db.php';

$currentYear = date("Y");

function getDaysInMonth($month, $year) 
{
    return date("t", strtotime("$year-$month-01"));
}

function getFirstDayOfMonth($month, $year) 
{
    return date("w", strtotime("$year-$month-01"));
}
//form submission 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_reminder'])) 
{
    $reminderTitle = $_POST['reminder_title'];
    $reminderDate = $_POST['reminder_date'];
    $reminderDesc = $_POST['reminder_desc'];
    $userId = $_SESSION['user_id'];

    //masuk reminder dalam database
    $stmt = $conn->prepare("INSERT INTO reminders (user_id, reminder_title, reminder_date, reminder_desc) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $userId, $reminderTitle, $reminderDate, $reminderDesc);
    if ($stmt->execute()) 
    {
        echo "<p style='color:green;'>Reminder added successfully!</p>";
    } 
    else 
    {
        echo "<p style='color:red;'>Failed to add reminder: " . $stmt->error . "</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Calendar</title>
    <style>
        body 
        {
            font-family: Helvetica, sans-serif;
            background-color: rgba(245, 245, 245, 0.69);
            padding: 20px;
            position: relative;
        }
        .calendar-container 
        {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 20px;
            justify-content: center;
        }
        .month 
        {
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .month-name 
        {
            text-align: center;
            font-size: 20px;
            margin-bottom: 10px;
        }
        .days 
        {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            grid-gap: 5px;
        }
        .day 
        {
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .day:hover 
        {
            background-color: lightgreen;
        }
        .day.empty 
        {
            background-color: #f0f0f0;
        }
        .reminder 
        {
            background-color: #d4edda;
            padding: 5px;
            border-radius: 5px;
            margin-top: 2px;
            font-size: 12px;
            color: #155724;
        }
        .reminder-desc 
        {
            font-size: 10px;
            color: #666;
            display: none;
        }
        .reminder-form 
        {
            margin-top: 20px;
            padding: 10px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: none;
        }
        .reminder-form input,
        .reminder-form textarea 
        {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .logout-btn 
        {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px 15px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .logout-btn:hover 
        {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <button class="logout-btn" onclick="confirmLogout()">Log Out</button>
    
    <h1>Welcome to Your Calendar, <?php echo $_SESSION['username']; ?>!</h1>
    
    <div class="calendar-container">

        <?php for ($month = 1; $month <= 12; $month++) : ?>
            <div class="month">
                <div class="month-name"><?php echo date("F", strtotime("$currentYear-$month-01")); ?></div>
                <div class="days">
                    <?php
                    // days
                    $daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                    foreach ($daysOfWeek as $day) 
                    {
                        echo "<div class='day'>$day</div>";
                    }

                    $firstDayOfMonth = getFirstDayOfMonth($month, $currentYear);
                    $totalDaysInMonth = getDaysInMonth($month, $currentYear);

                    //empty slot before first day of the month
                    for ($i = 0; $i < $firstDayOfMonth; $i++) 
                    {
                        echo "<div class='day empty'></div>";
                    }

                    // output days of month
                    for ($day = 1; $day <= $totalDaysInMonth; $day++) 
                    {
                        $date = "$currentYear-$month-" . str_pad($day, 2, "0", STR_PAD_LEFT);
                        echo "<div class='day' onclick='showReminderForm(\"$date\")'>$day";

                        //get reminders from database
                        $stmt = $conn->prepare("SELECT * FROM reminders WHERE user_id = ? AND reminder_date = ?");
                        $stmt->bind_param("is", $_SESSION['user_id'], $date);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($reminder = $result->fetch_assoc()) 
                        {
                            echo "<div class='reminder'>{$reminder['reminder_title']}
                                    <div class='reminder-desc'>{$reminder['reminder_desc']}</div>
                                  </div>";
                        }
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        <?php endfor; ?>

    </div>

    <!-- reminder form -->
    <div id="reminderForm" class="reminder-form">
        <h3>Add Reminder</h3>
        <form method="POST">
            <input type="hidden" name="reminder_date" id="reminder_date">
            <input type="text" name="reminder_title" placeholder="Reminder Title" required><br>
            <textarea name="reminder_desc" placeholder="Reminder Description" rows="4" required></textarea><br>
            <button type="submit" name="add_reminder">Add Reminder</button>
        </form>
    </div>

    <script>
        // show reminder form when clicked
        function showReminderForm(date) 
        {
            document.getElementById('reminder_date').value = date;
            document.getElementById('reminderForm').style.display = 'block';
        }

        //reminder_desc toggle
        function toggleDescription(elem)
        {
            var desc = elem.querySelector('.reminder-desc');
            if (desc.style.display === "none") {
                desc.style.display = "block";
            } else {
                desc.style.display = "none";
            }
        }

        //eventlistener to toggle desc
        document.querySelectorAll('.reminder').forEach(function (reminder) 
        {
            reminder.addEventListener('click', function () 
            {
                toggleDescription(reminder);
            });
        });

        //logout
        function confirmLogout() 
        {
            if (confirm("Are you sure you want to log out?")) 
            {
                window.location.href = 'logout.php';  //redirect to logout page
            }
        }
    </script>

</body>
</html>
