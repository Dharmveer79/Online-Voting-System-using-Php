<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Online Voting System</title>

    <?php include('./header.php'); ?>
    <?php 
    session_start();
    if(isset($_SESSION['login_id']))
    header("location:index.php?page=home");
    ?>

    <style>
        /* Import Google Font */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        /* Reset Default Styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: linear-gradient(135deg, #1e1e2f, #3a0ca3);
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
        }

        /* Background Bubbles */
        body::before {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            background: url('assets/img/bg-pattern.png');
            opacity: 0.1;
            z-index: -1;
        }

        /* Glassmorphism Login Box */
        .login-container {
            width: 400px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
            text-align: center;
            animation: fadeIn 1s ease-in-out;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-container img {
            width: 90px;
            height: auto;
            margin-bottom: 10px;
            filter: drop-shadow(0px 0px 5px #ffffff);
        }

        h3 {
            font-weight: 700;
            font-size: 1.7rem;
            color: #fff;
            text-shadow: 0px 0px 10px rgba(255, 255, 255, 0.7);
            margin-bottom: 20px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: 600;
            color: #fff;
            display: block;
            margin-bottom: 5px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            transition: 0.3s;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            outline: none;
            box-shadow: inset 0 0 10px rgba(255, 255, 255, 0.1);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control:focus {
            border: none;
            outline: none;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.7);
        }

        .remember-me {
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #fff;
            font-size: 0.9rem;
        }

        .remember-me input {
            margin-right: 5px;
        }

        .btn-primary {
            background: linear-gradient(45deg, #007bff, #6610f2);
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
            color: white;
            width: 100%;
            transition: 0.3s;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 15px;
            box-shadow: 0px 5px 15px rgba(0, 123, 255, 0.4);
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #6610f2, #007bff);
            transform: scale(1.05);
            box-shadow: 0px 8px 20px rgba(0, 123, 255, 0.6);
        }

        .alert-danger {
            background: #ff4d4d;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 10px;
        }

        .forgot-password {
            text-align: right;
            margin-top: 5px;
        }

        .forgot-password a {
            text-decoration: none;
            font-size: 0.9rem;
            color: #00d4ff;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <!-- Logo & Heading -->
        <div class="logo-container">
            <img src="assets/img/IIIT_Surat_logo.jpg" alt="IIIT Surat Logo">
            <h3>Online Voting System</h3>
        </div>

        <!-- Login Form -->
        <form id="login-form">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Enter Username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password">
            </div>
            <div class="remember-me">
                <label><input type="checkbox" id="remember-me"> Remember Me</label>
                <div class="forgot-password">
                    <a href="#">Forgot Password?</a>
                </div>
            </div>
            <button type="submit" class="btn-primary">Login</button>
        </form>
    </div>

</body>

<script>
    document.getElementById("login-form").addEventListener("submit", function(e) {
        e.preventDefault();
        const button = document.querySelector("button[type='submit']");
        button.disabled = true;
        button.innerHTML = "Logging in...";

        fetch('ajax.php?action=login', {
            method: 'POST',
            body: new FormData(this),
        })
        .then(response => response.text())
        .then(resp => {
            if (resp == 1) {
                window.location.href = 'index.php?page=home';
            } else if (resp == 2) {
                window.location.href = 'voting.php';
            } else {
                alert("Invalid Credentials!");
                button.disabled = false;
                button.innerHTML = "Login";
            }
        })
        .catch(error => {
            console.error(error);
            button.disabled = false;
            button.innerHTML = "Login";
        });
    });
</script>

</html>
