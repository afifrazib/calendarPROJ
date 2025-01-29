<!DOCTYPE html>
<html>
<head>
    <title>Sign up</title>
    <style>
        body 
        {
            font-family: Helvetica, sans-serif;
            background-color: rgba(245, 245, 245, 0.69);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .signup-container 
        {
            width: 100%;
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .signup-container h1 
        {
            text-align: center;
            margin-bottom: 20px;
            color: black;
        }
        .form-group 
        {
            margin-bottom: 15px;
        }
        .form-group label 
        {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: black;
        }
        .form-group input 
        {
            width: 90%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid black;
            border-radius: 10px;
        }
        .btn 
        {
            display: block;
            width: 50%;
            padding: 8px;
            font-size: 20px;
            color: white;
            background-color: green;
            border: none;
            border-radius: 10px;
            cursor: pointer;

        }
        .btn:hover 
        {
            background-color: lightgreen;
        }
        .text-center 
        {
            text-align: center;
            margin-top: 10px;
        }
        .text-center a 
        {
            color: lightgreen;
            text-decoration: none;
        }
        .text-center a:hover 
        {
            text-decoration: underline;
        }
        .success-message 
        {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            text-align: center;
            margin-top: 20px;
            align-self: center;
            display: block;
        }
        .failed-message 
        {
            margin-top: 15px;
            background-color: rgb(237, 212, 212);
            color: rgb(87, 21, 21);
            padding: 10px;
            border: 1px solid rgb(0, 0, 0);
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="signup-container">
        <h1>Sign up</h1>

        <form method="POST" action="signup_process.php">
            <div class="form-group">
                <label for="username">Username: </label>
                <input type="text" name="username" id="username" placeholder="Enter your username" required><br>
            </div>

            <div class="form-group">
                <label for="email">Email: </label>
                <input type="email" name="email" id="email" placeholder="Enter your email" required><br>
            </div>

            <div class="form-group">
                <label for="password">Password: </label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required><br>
            </div>

            <button type="submit" class="btn">Sign up</button>

            <div class="text-center">
                <p>Already have an account? <a href="login.php">Log in here</a></p>
            </div>

        </form>

        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] == 'success'): ?>
                <div class="success-message">
                    Sign-up successful! <a href="login.php">Login here</a>
                </div>
            <?php elseif ($_GET['status'] == 'failed'): ?>
                <div class="failed-message">
                    Sign-up failed. Please try again.
                </div>
            <?php elseif ($_GET['status'] == 'empty_fields'): ?>
                <div class="failed-message">
                    Please fill in all fields.
                </div>
            <?php elseif ($_GET['status'] == 'username_taken'): ?>
                <div class="failed-message">
                    The username is already taken. Please choose another one.
                </div>
            <?php elseif ($_GET['status'] == 'email_taken'): ?>
                <div class="failed-message">
                    The email is already registered. Please use a different email.
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</body>
</html>
