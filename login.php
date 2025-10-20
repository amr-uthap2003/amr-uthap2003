<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php
session_start();
require_once 'user.php';
$user = new User();
$message = "";

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $loggedUser = $user->login($email,$password);
    if($loggedUser) {
        $_SESSION['user_id'] = $loggedUser['id'];
        $_SESSION['username'] = $loggedUser['username'];
        header("Location: index.php");
        exit();
    } else {
        $message = "Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f3f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
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
        input[type="email"], input[type="password"] {
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
    <div class="login-container">
        <h2>Login</h2>
        <?php if($message != ""): ?>
            <p class="error-message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="POST" id="loginForm">
            <input type="email" name="email" id="email" placeholder="Email" required>
            <div id="emailError" class="live-error"></div>

            <input type="password" name="password" id="password" placeholder="Password" required>
            <div id="passError" class="live-error"></div>

            <input type="submit" name="login" value="Login">
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>

    <script>
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const emailError = document.getElementById('emailError');
        const passError = document.getElementById('passError');

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


