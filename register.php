<?php
require_once 'user.php';
$user = new user();
$message = "";

if(isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if($user->register($username,$email,$password)) {
        header("Location: login.php");
        exit();
    } else {
        $message = "Registration failed. Email or username may already exist.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f3f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-container {
            background-color: white;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0px 5px 20px rgba(0,0,0,0.1);
            width: 350px;
        }
        h2 {
            text-align: center;
            color: #2874f0;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px 10px;
            margin: 8px 0 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #2874f0;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #0b5ed7;
        }
        p {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        p a {
            color: #2874f0;
            text-decoration: none;
            font-weight: bold;
        }
        p a:hover {
            text-decoration: underline;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
        .live-error {
            color: red;
            font-size: 13px;
            margin-bottom: 8px;
        }
        .valid {
            color: green;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <?php if($message != ""): ?>
            <p class="error-message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="POST" id="registerForm">
            <input type="text" name="username" id="username" placeholder="Username" required>
            <div id="userError" class="live-error"></div>

            <input type="email" name="email" id="email" placeholder="Email" required>
            <div id="emailError" class="live-error"></div>

            <input type="password" name="password" id="password" placeholder="Password" required>
            <div id="passError" class="live-error"></div>

            <input type="submit" name="register" value="Register">
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>

    <script>
        const username = document.getElementById('username');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const userError = document.getElementById('userError');
        const emailError = document.getElementById('emailError');
        const passError = document.getElementById('passError');

        // Live username validation
        username.addEventListener('input', () => {
            if(username.value.trim().length < 3) {
                userError.textContent = "Username must be at least 3 characters.";
                userError.classList.remove('valid');
            } else {
                userError.textContent = "Looks good!";
                userError.classList.add('valid');
            }
        });

        // Live email validation
        email.addEventListener('input', () => {
            const pattern = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/;
            if(!pattern.test(email.value.trim())) {
                emailError.textContent = "Enter a valid email address.";
                emailError.classList.remove('valid');
            } else {
                emailError.textContent = "Valid email!";
                emailError.classList.add('valid');
            }
        });

        // Live password validation
        password.addEventListener('input', () => {
            if(password.value.length < 6) {
                passError.textContent = "Password must be at least 6 characters.";
                passError.classList.remove('valid');
            } else {
                passError.textContent = "Strong password!";
                passError.classList.add('valid');
            }
        });
    </script>
</body>
</html>

