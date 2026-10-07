<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: Home.php");
    exit();
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nume = $_POST['nume_joc'];
    $cat_id = $_POST['category_id'];
    $plat = $_POST['platform'];
    $pret = $_POST['price'];
    $desc = $_POST['description'] ?? '';

    $img = 'pngs/default.png'; 
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'pngs/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = preg_replace("/[^a-zA-Z0-9.]/", "_", basename($_FILES['image_file']['name']));
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetPath)) {
            $img = $targetPath;
        }
    }

    if (!empty($nume)) {
        $stmt = $pdo->prepare("INSERT INTO products (name, category_id, platform, price, image_path, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nume, $cat_id, $plat, $pret, $img, $desc]);
        header("Location: Store.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Adăugare Joc Nou</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body { background-color: #0a0a0a; color: white; font-family: Arial; }
        .container { max-width: 450px; margin: 50px auto; background: #1a1a1a; padding: 30px; border: 1px solid #00ff00; border-radius: 8px; }
        input, select { width: 100%; padding: 10px; margin: 10px 0; background: #222; border: 1px solid #444; color: white; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #00ff00; color: black; border: none; font-weight: bold; cursor: pointer; margin-top: 10px; }
        .back { display: block; text-align: center; margin-top: 15px; color: #00ff00; text-decoration: none; }
        label { font-size: 0.9em; color: #aaa; }
    </style>
</head>
<body>
    <div class="container">
        <h3>Adaugă un joc nou</h3>
        <form method="POST" enctype="multipart/form-data">
            <label>Titlu Joc:</label>
            <input type="text" name="nume_joc" required>
            
            <label>Categorie:</label>
            <select name="category_id">
                <?php foreach($categories as $c): ?>
                    <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
                <?php endforeach; ?>
            </select>

            <label>Platformă:</label>
            <input type="text" name="platform" placeholder="Ex: PC, PS5" required>

            <label>Preț:</label>
            <input type="text" name="price" placeholder="Ex: 29.99€" required>

            <label>Descriere Joc:</label>
            <textarea name="description" rows="4" style="width: 100%; background: #222; color: white; border: 1px solid #444; padding: 10px; box-sizing: border-box;"></textarea>

            <label>Încarcă Imagine Joc (png, jpg, webp):</label>
            <input type="file" name="image_file" accept="image/png, image/jpeg, image/webp">

            <button type="submit">Salvează Jocul</button>
        </form>
        <a href="Store.php" class="back">← Renunță</a>
    </div>
</body>
</html>