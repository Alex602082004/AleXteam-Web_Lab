<?php
session_start();
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = trim($_POST['user']);
    $pass = trim($_POST['pass']);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$user]);
    $userData = $stmt->fetch();

    if ($userData && password_verify($pass, $userData['password'])) {
        $_SESSION['user_id']  = $userData['id'];
        $_SESSION['role'] = $userData['role'];
        $_SESSION['username'] = $userData['username'];
        $_SESSION['profile_image'] = $userData['profile_image'];
        header("Location: Home.php");
        exit();
    } else {
        $error = "Utilizator sau parolă incorectă!"; 
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EA FC 26 - Login HTML5</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        

        main {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-card {
            background-color: #1a1a1a;
            border: 1px solid #333;
            padding: 30px;
            width: 100%;
            max-width: 300px;
            text-align: center;
            border-radius: 4px;
        }

        .login-card h3 {
            margin-top: 0;
            color: #00ff00;
            text-transform: uppercase;
        }

        .form-group {
            text-align: left;
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-size: 0.9em;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            background-color: #2a2a2a;
            border: 1px solid #444;
            color: white;
            box-sizing: border-box;
        }

        .login-btn {
            background-color: #00ff00;
            color: black;
            border: none;
            padding: 10px;
            width: 100%;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }

        .login-btn:hover {
            background-color: #00cc00;
        }

        .back-link {
            margin-top: 20px;
        }

        .back-link a {
            color: #00ff00;
            text-decoration: none;
            font-size: 0.9em;
        }
    </style>
</head>
<body>

    <main>
        <div class="login-card">
            <form method="post">
                <h3>Autentificare</h3>
                <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
                
                <div class="form-group">
                    <label>Utilizator:</label>
                    <input type="text" name="user" required>
                </div>

                <div class="form-group">
                    <label>Parolă:</label>
                    <input type="password" name="pass" required>
                </div>

                <button type="submit" class="login-btn">Log In</button>
            </form>
        </div>

        <div class="back-link">
            <p><a href="Home.php">← Înapoi la pagina principală</a></p>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 AleXteam</p>
    </footer>

</body>
</html>