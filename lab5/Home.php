<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EA FC 26 - Home HTML5</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        header h1::before {
            content: "🎮 ";
            margin-right: 10px;
            vertical-align: 4px;
            display: inline-block;
        }

        nav {
            margin-top: 10px;
        }

        main {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        main h2 {
            font-size: 2em;
        }

        main a {
            color: #00ff00;
            text-decoration: none;
            font-weight: bold;
        }

        .slider-container {
            margin-top: 50px;
            width: 100%;
            max-width: 700px;
            background-color: #1a1a1a;
            padding: 20px;
            border: 1px solid #333;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }

        .large-image-display {
            width: 100%;
            margin-bottom: 20px;
            border: 2px solid #00ff00;
            border-radius: 4px;
            overflow: hidden;
            background-color: #000;
        }

        .large-image-display img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            background-color: #000;
            transition: opacity 0.3s ease;
        }

        .thumbnail-row {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .thumbnail {
            flex: 1;
            max-width: 150px;
            height: 100px;
            cursor: pointer;
            border: 2px solid #333;
            border-radius: 4px;
            overflow: hidden;
            transition: border-color 0.3s ease, transform 0.2s ease;
        }

        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .thumbnail:hover {
            border-color: #00ff00;
            transform: scale(1.05);
        }

        .platforms {
            margin-top: 50px;
            text-align: center;
        }

        .platforms h3 {
            color: #00ff00;
            font-size: 1.5em;
        }

        .platform-icons {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 20px;
        }

        .platform-icons img {
            transition: transform 0.3s ease;
            object-fit: contain;
        }

        .platform-icons img:hover {
            transform: scale(1.1);
        }

        .social-corner {
            position: absolute;
            top: 12px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
            background-image: url('pngs/social-sprite.svg');
            background-repeat: no-repeat;
            background-size: 80px 40px;
        }

        .social-facebook {
            left: 12px;
            background-position: 0 0;
        }

        .social-linkedin {
            right: 12px;
            background-position: -40px 0;
        }

        .social-corner a {
            display: block;
            width: 100%;
            height: 100%;
        }

        .social-corner:hover {
            transform: scale(1.1);
        }

        .slider-controls {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #333;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            color: white;
            font-size: 0.9em;
        }

        .slider-controls button {
            background-color: #00ff00;
            color: black;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            min-width: 100px;
        }

        .slider-controls select {
            background: #2a2a2a;
            color: white;
            border: 1px solid #444;
            padding: 5px;
            border-radius: 4px;
        }

        .lab-btn-container {
            margin-top: 50px;
        }

        .lab-link-btn {
            display: inline-block;
            padding: 15px 30px;
            background-color: transparent;
            border: 2px solid #00ff00;
            color: #00ff00;
            text-decoration: none;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .lab-link-btn:hover {
            background-color: #00ff00;
            color: #000;
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.4);
        }
    </style>
</head>

<body>

    <header>
        <h1>BINE AI VENIT LA ALEXTEAM</h1>
        <nav>
            <a href="Home.php">Acasă</a> |
            <a href="Store.php">Magazin</a> |
            <?php if(!isset($_SESSION['user_id'])): ?>
                <a href="Register.php">Cont Nou</a> |
                <a href="LogIn.php">Autentificare</a> |
            <?php elseif(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="Add.php">Adăugare Joc</a> |
            <?php endif; ?>
            <a href="Watchgameplay.php" style="color: #00ff00;">Watch Gameplay</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                | <a href="Logout.php">Logout</a>
            <?php endif; ?>
        </nav>
    </header>

    <?php if(isset($_SESSION['user_id'])): ?>
    <div class="user-profile-widget">
        <a href="Profile.php">
            <img src="<?php echo htmlspecialchars($_SESSION['profile_image'] ?? 'pngs/default_user.jpg'); ?>" alt="Profile">
            <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </a>
    </div>
    <?php endif; ?>

    <div class="social-corner social-facebook" title="Facebook">
        <a href="https://www.facebook.com" target="_blank"></a>
    </div>
    <div class="social-corner social-linkedin" title="LinkedIn">
        <a href="https://www.linkedin.com" target="_blank"></a>
    </div>

    <main>
        <h2>Noua eră a gamingului.</h2>
        <p>Explorează cel mai mare magazin online de jocuri video.</p>
        <p><a href="Store.php">Vezi oferta în magazin &raquo;</a></p>

        <div class="slider-container">
            <div class="large-image-display">
                <img id="mainImage" src="pngs/ea_fc26.webp" alt="EA FC 26">
            </div>
            <div class="thumbnail-row">
                <div class="thumbnail" onmouseover="changeImage('pngs/ea_fc26.webp')">
                    <img src="pngs/ea_fc26.webp" alt="EA FC 26">
                </div>
                <div class="thumbnail" onmouseover="changeImage('pngs/csgo.png')">
                    <img src="pngs/csgo.png" alt="CS2">
                </div>
                <div class="thumbnail" onmouseover="changeImage('pngs/rocket.png')">
                    <img src="pngs/rocket.png" alt="Rocket League">
                </div>
                <div class="thumbnail" onmouseover="changeImage('pngs/valorant.png')">
                    <img src="pngs/valorant.png" alt="Valorant">
                </div>
            </div>
            
            <div class="slider-controls">
                <button id="playPauseBtn"><i class="fa-solid fa-play"></i> Play</button>
                
                <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                    <input type="checkbox" id="repeatCheck" checked> Repetă
                </label>

                <label style="display: flex; align-items: center; gap: 10px;">
                    Interval:
                    <select id="intervalSelect">
                        <option value="1000">1s</option>
                        <option value="2000" selected>2s</option>
                        <option value="3000">3s</option>
                        <option value="5000">5s</option>
                    </select>
                </label>
            </div>
        </div>

        <div class="platforms">
            <h3>Disponibil pe</h3>
            <div class="platform-icons">
                <img src="pngs/steam.png" alt="Steam" style="width: 50px; height: 50px;">
                <img src="pngs/epic.svg" alt="Epic Games" style="width: 100px; height: 50px;">
                <img src="pngs/ea.png" alt="EA Sports" style="width: 50px; height: 50px;">
            </div>
        </div>

        <div class="lab-btn-container">
            <a href="Formulare.php" class="lab-link-btn">Formulare Trivia</a>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 AleXteam</p>
    </footer>

    <script>
        const images = ['pngs/ea_fc26.webp', 'pngs/csgo.png', 'pngs/rocket.png', 'pngs/valorant.png'];
        let currentIndex = 0;
        let timerId = null;

        function changeImage(src) {
            document.getElementById('mainImage').src = src;
            currentIndex = images.indexOf(src);
        }

        function nextImage() {
            currentIndex++;
            
            if (currentIndex >= images.length) {
                if (document.getElementById('repeatCheck').checked) {
                    currentIndex = 0;
                } else {
                    toggleSlideshow(); 
                    return;
                }
            }
            
            document.getElementById('mainImage').src = images[currentIndex];
        }

        function toggleSlideshow() {
            const btn = document.getElementById('playPauseBtn');
            
            if (timerId) {
                clearInterval(timerId);
                timerId = null;
                btn.innerHTML = '<i class="fa-solid fa-play"></i> Play';
            } else {
                const interval = parseInt(document.getElementById('intervalSelect').value);
                timerId = setInterval(nextImage, interval);
                btn.innerHTML = '<i class="fa-solid fa-pause"></i> Pauză';
            }
        }

        document.getElementById('playPauseBtn').addEventListener('click', toggleSlideshow);

        document.getElementById('intervalSelect').addEventListener('change', () => {
            if (timerId) {
                toggleSlideshow();
                toggleSlideshow();
            }
        });
    </script>

</body>

</html>