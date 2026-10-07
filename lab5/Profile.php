<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: LogIn.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cropped_image'])) {
    $new_username = trim($_POST['username']);
    $profile_image = $_SESSION['profile_image']; 

    $data = $_POST['cropped_image'];
    if (strpos($data, 'data:image/png;base64,') === 0) {
        $data = str_replace('data:image/png;base64,', '', $data);
        $data = base64_decode($data);
        $fileName = 'pngs/avatar_' . $user_id . '_' . time() . '.png';
        file_put_contents($fileName, $data);
        $profile_image = $fileName;
    }

    $stmt = $pdo->prepare("UPDATE users SET username = ?, profile_image = ? WHERE id = ?");
    if ($stmt->execute([$new_username, $profile_image, $user_id])) {
        $_SESSION['username'] = $new_username;
        $_SESSION['profile_image'] = $profile_image;
        $success = "Profil actualizat cu succes!";
    }
}

if (isset($_POST['delete_photo'])) {
    if ($_SESSION['profile_image'] !== 'pngs/default_user.jpg' && file_exists($_SESSION['profile_image'])) {
        unlink($_SESSION['profile_image']);
    }
    $default = 'pngs/default_user.jpg';
    $stmt = $pdo->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
    $stmt->execute([$default, $user_id]);
    $_SESSION['profile_image'] = $default;
    $success = "Poza a fost ștearsă!";
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Editare Profil - <?php echo htmlspecialchars($_SESSION['username']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .profile-container {
            max-width: 500px;
            margin: 50px auto;
            background: #1a1a1a;
            padding: 30px;
            border: 1px solid #00ff00;
            border-radius: 8px;
            text-align: center;
        }
        .current-avatar {
            width: 130px !important;
            height: 130px !important;
            border-radius: 50% !important;
            border: 4px solid #00ff00 !important;
            margin: 0 auto 20px auto !important;
            object-fit: cover !important;
            box-shadow: 0 0 20px #00ff00 !important;
            display: block;
        }
        input, button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .save-btn { background: #00ff00; color: black; border: none; font-weight: bold; cursor: pointer; }
        .del-btn { background: #ff4444; color: white; border: none; cursor: pointer; }
        .back-link { color: #00ff00; text-decoration: none; margin-top: 20px; display: block; }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>Editare Profil</h2>
        <?php if($success) echo "<p style='color: #00ff00'>$success</p>"; ?>
        
        <img src="<?php echo $_SESSION['profile_image']; ?>" class="current-avatar" alt="Avatar">
        
        <form method="POST" id="profileForm">
            <label>Nume Utilizator:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
            
            <label>Schimbă poza de profil:</label>
            <input type="file" id="upload-avatar" accept="image/*">
            
            <div id="cropper-container">
                <img id="image-to-crop" style="max-width: 100%;">
            </div>
            
            <input type="hidden" name="cropped_image" id="cropped_image">
            <button type="button" id="crop-and-save" class="save-btn">Salvează Modificările</button>
            
            <?php if($_SESSION['profile_image'] !== 'pngs/default_user.jpg'): ?>
                <button type="submit" name="delete_photo" class="del-btn" onclick="return confirm('Ștergi poza actuală?')">Șterge poza și revino la default</button>
            <?php endif; ?>
        </form>
        
        <a href="Home.php" class="back-link">← Înapoi la Acasă</a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        let cropper;
        const fileInput = document.getElementById('upload-avatar');
        const imageToCrop = document.getElementById('image-to-crop');
        const cropperContainer = document.getElementById('cropper-container');
        const hiddenInput = document.getElementById('cropped_image');
        const saveBtn = document.getElementById('crop-and-save');

        fileInput.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    imageToCrop.src = event.target.result;
                    cropperContainer.style.display = 'block';
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(imageToCrop, {
                        aspectRatio: 1, 
                        viewMode: 1,
                        autoCropArea: 1
                    });
                };
                reader.readAsDataURL(files[0]);
            }
        });

        saveBtn.addEventListener('click', function() {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 150,
                    height: 150
                });
                hiddenInput.value = canvas.toDataURL('image/png');
            }
            document.getElementById('profileForm').submit();
        });
    </script>
</body>
</html>