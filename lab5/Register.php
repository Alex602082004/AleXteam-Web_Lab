<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = trim($_POST['n1']); 
    $pass = $_POST['p1'];
    $pass2 = $_POST['p2'];

    if ($pass !== $pass2) {
        $error = "Parolele nu coincid!";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$user]);
        if ($stmt->fetch()) {
            $error = "Acest nume de utilizator este deja luat!";
        } else {
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
            $stmt->execute([$user, $hashed_password]);
            header("Location: LogIn.php?registered=success");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EA FC 26 - Înregistrare HTML5</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        header {
            text-align: center;
            padding: 20px;
        }

        header h1 {
            color: #00ff00;
        }

        main {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .form-container {
            background-color: #1a1a1a;
            border: 2px solid #00ff00;
            padding: 30px;
            border-radius: 8px;
            width: 100%;
            max-width: 500px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="date"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            background-color: #2a2a2a;
            border: 1px solid #444;
            color: white;
            box-sizing: border-box; 
            transition: border-color 0.3s, box-shadow 0.3s, background-color 0.3s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #00ff00;
            outline: none;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.4);
            background-color: #333;
        }

        .radio-group, .checkbox-group {
            margin: 10px 0;
        }

        .submit-btn {
            background-color: #00ff00;
            color: black;
            border: none;
            padding: 10px 20px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background-color: #00cc00;
        }
    </style>
</head>
<body>

    <header>
        <h1>Creează un cont nou</h1>
        <nav>
            <a href="Home.php" style="color: #00ff00; text-decoration: none; font-weight: bold;">Acasă</a> | 
            <a href="Store.php" style="color: #00ff00; text-decoration: none; font-weight: bold;">Magazin</a> |
            <?php if(!isset($_SESSION['user_id'])): ?>
                <a href="LogIn.php" style="color: #00ff00; text-decoration: none; font-weight: bold;">Autentificare</a>
            <?php else: ?>
                <a href="Logout.php" style="color: #ff4444; text-decoration: none; font-weight: bold;">Logout</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <form method="post">
                <?php if(isset($error)) echo "<p style='color: #ff4444; font-weight: bold;'>$error</p>"; ?>
                <div class="form-group">
                    <label>1. Utilizator (Username):</label>
                    <input type="text" name="n1" required>
                </div>

                <div class="form-group">
                    <label>2. Prenume:</label>
                    <input type="text" name="n2" required>
                </div>

                <div class="form-group">
                    <label>3. Email:</label>
                    <input type="email" name="e1" required>
                </div>

                <div class="form-group">
                    <label>4. Parolă:</label>
                    <input type="password" name="p1" required>
                </div>

                <div class="form-group">
                    <label>5. Confirmă Parolă:</label>
                    <input type="password" name="p2" required>
                </div>

                <div class="form-group">
                    <label>6. Data Nașterii:</label>
                    <input type="date" name="d1">
                </div>

                <div class="radio-group">
                    <label>7. Gen:</label>
                    <input type="radio" name="g" value="m"> M 
                    <input type="radio" name="g" value="f" style="margin-left: 10px;"> F
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" name="c1" required> Sunt de acord cu termenii.
                </div>

                <div class="form-group">
                    <label>12. Bio:</label>
                    <textarea name="t1" rows="3">Mă numesc Alex și îmi place fotbalul.</textarea>
                </div>

                <div class="form-group">
                    <label>14. Nivel experiență:</label>
                    <input type="number" name="v1" min="1" max="99" value="1">
                </div>

                <button type="submit" class="submit-btn">Trimite Datele</button>
            </form>
        </div>
    </main>

    <footer><p>&copy; 2026 AleXteam</p></footer>
</body>
</html>