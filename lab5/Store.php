<?php
session_start();
include 'db.php';

$stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id");
$all_products = $stmt->fetchAll();

$stmtMain = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = 1");
$stmtMain->execute();
$main_product = $stmtMain->fetch() ?: []; 
?>
<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FC 26 Central - Vizualizare Produs (Antony Edition)</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            padding: 0;
        }

        header h1 {
            margin: 0 0 10px 0;
        }

        nav a {
            padding: 0 10px;
        }

        .container {
            display: flex;
            max-width: 95%;
            margin: 20px auto;
            background-color: #1a1a1a;
            border: 1px solid #333333;
            flex: 1;
            align-items: flex-start;
        }

        aside {
            width: 250px;
            background-color: #111111;
            padding: 20px;
            border-right: 1px solid #333333;
            align-self: stretch;
        }

        main {
            flex: 1;
            padding: 20px;
        }

        .product-card {
            border: 1px solid #00ff00;
            padding: 20px;
            background-color: #111111;
        }

        .product-header {
            background-color: #00ff00;
            color: #000000;
            text-align: center;
            padding: 15px;
            margin-bottom: 20px;
        }

        .product-details {
            display: flex;
            gap: 30px;
            align-items: flex-start;
        }

        .cover-section {
            text-align: center;
            width: 220px;
            flex-shrink: 0;
        }

        .cover-section img {
            max-width: 100%;
            height: auto;
            border: 2px solid #333;
            border-radius: 4px;
        }

        .stats-table,
        .pricing-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #1a1a1a;
            margin-top: 10px;
        }

        .stats-table th,
        .stats-table td,
        .pricing-table th,
        .pricing-table td {
            border: 1px solid #333333;
            padding: 12px;
            text-align: left;
        }
        
        .stats-table th {
            cursor: pointer;
            user-select: none;
        }

        .stats-table th,
        .pricing-table th {
            background-color: #222222;
            color: #00ff00;
            text-transform: uppercase;
            font-size: 0.9em;
        }

        .pricing-section {
            margin-top: 30px;
            border-top: 1px solid #333;
            padding-top: 20px;
        }

        .admin-links {
            text-align: right;
            margin-top: 30px;
            font-size: 0.9em;
        }

        .admin-links a {
            padding: 5px 15px;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .admin-links a:hover {
            background: rgba(0, 255, 0, 0.1);
        }

        .games-section {
            margin-top: 40px;
            width: 100%;
        }

        .games-section h3 {
            text-align: center;
            color: #00ff00;
            margin-bottom: 20px;
        }

        .games-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #111111;
        }

        .games-table th {
            background-color: #00ff00;
            color: #000;
            padding: 15px;
            text-align: left;
            font-weight: bold;
        }

        .games-table td {
            padding: 15px;
            border-bottom: 1px solid #333;
            vertical-align: middle;
        }

        .games-table tr {
            position: relative;
            transition: background-color 0.3s ease;
        }

        .games-table tbody tr:hover {
            background-color: rgba(0, 255, 0, 0.05);
        }

        .game-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #333;
        }

        .game-info-container {
            position: relative;
        }

        .game-info-tooltip {
            display: none;
            position: absolute;
            bottom: 100%;
            left: 0;
            background-color: #1a1a1a;
            border: 2px solid #00ff00;
            padding: 10px;
            border-radius: 4px;
            width: 200px;
            z-index: 100;
            margin-bottom: 5px;
            font-size: 0.85em;
            color: #aaa;
        }

        .news-section {
            margin-top: 50px;
            padding: 30px;
            background-color: #111;
            border: 1px solid #333;
            border-radius: 8px;
            text-align: center;
        }

        .news-list {
            list-style: none;
            padding: 0;
            margin: 20px auto;
            min-height: 100px;
            max-width: 600px;
        }

        .news-item {
            display: none; 
            opacity: 0;
        }

        .news-item.active {
            display: block; 
            animation: fadeInNews 0.8s forwards; 
        }

        @keyframes fadeInNews {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .news-text {
            color: #fff;
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .read-more {
            color: #00ff00;
            text-decoration: none;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.85em;
        }

        .news-btn {
            background-color: #00ff00;
            color: #000;
            border: none;
            padding: 10px 25px;
            margin: 10px 5px 0;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }

        .news-btn:hover {
            background-color: #00cc00;
        }

        .game-info-container:hover .game-info-tooltip {
            display: block;
        }

        @media (max-width: 480px) {

            .games-table,
            .games-table thead,
            .games-table tbody,
            .games-table th,
            .games-table td,
            .games-table tr {
                display: block;
            }

            .games-table thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            .games-table tr {
                border: 1px solid #333;
                margin-bottom: 15px;
                border-radius: 8px;
                background: #222;
            }

            .games-table td {
                border: none;
                position: relative;
                padding-left: 50%;
                text-align: right;
            }

            .game-image {
                width: 80px;
                height: 80px;
                margin: 10px auto;
                display: block;
            }
        }

        @media (max-width: 768px) {
            .games-table {
                font-size: 0.9em;
            }

            .games-table th,
            .games-table td {
                padding: 10px;
            }

            .game-image {
                width: 60px;
                height: 60px;
            }

            .game-info-tooltip {
                width: 150px;
            }
        }

        .widget-card {
            background-color: #1a1a1a;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 15px;
            margin-top: 25px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, border-color 0.3s ease;
        }

        .widget-card:hover {
            transform: translateY(-5px);
            border-color: #00ff00;
        }

        .widget-card h4 {
            color: #00ff00;
            margin: 0 0 10px 0;
            font-size: 1.1em;
            text-transform: uppercase;
            border-bottom: 1px solid #333;
            padding-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-dot {
            height: 10px;
            width: 10px;
            background-color: #00ff00;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 8px #00ff00;
        }

        .widget-content {
            font-size: 0.85em;
            color: #ccc;
            line-height: 1.6;
        }

        .widget-content p {
            margin: 5px 0;
        }

        .offer-badge {
            background-color: #ff0000;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 0.8em;
        }
    </style>
</head>

<body>

    <header>
        <h1>EA SPORTS FC™ 26</h1>
        <nav>
            <a href="Home.php">Acasă</a> |
            <a href="Store.php">Magazin</a> |
            <a href="Watchgameplay.php" style="color: #00ff00;">Watch Gameplay</a> |
            <?php if(!isset($_SESSION['user_id'])): ?>
                <a href="Register.php">Cont Nou</a> |
                <a href="LogIn.php">Autentificare</a> |
            <?php elseif($_SESSION['role'] === 'admin'): ?>
                <a href="Add.php">Adăugare Joc</a> |
            <?php endif; ?>
            <a href="https://www.ea.com/games/ea-sports-fc" target="_blank">Site Oficial EA</a>
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

    <div class="container">
        <aside>
            <h2>Moduri de Joc</h2>
            <ol type="A">
                <li>Single Player
                    <ul style="list-style-type: circle;">
                        <li>Manager Career</li>
                        <li>Player Career</li>
                    </ul>
                </li>
                <li>Multiplayer Online
                    <ol type="I">
                        <li>Ultimate Team (UT)</li>
                        <li>Clubs & Volta</li>
                    </ol>
                </li>
            </ol>
            <hr>
            <p><strong>Platforme:</strong></p>
            <ul style="list-style-type: square;">
                <li>Consolă
                    <ol type="a">
                        <li>PlayStation 5</li>
                        <li>Xbox Series X/S</li>
                    </ol>
                </li>
            </ul>

            <div class="widget-card">
                <h4><span class="status-dot"></span> Status Server</h4>
                <div class="widget-content">
                    <p>Europa: Online</p>
                    <p>America: Online</p>
                    <p>Latență: 24ms</p>
                </div>
            </div>

            <div class="widget-card">
                <h4><span class="offer-badge">HOT</span> Oferta Zilei</h4>
                <div class="widget-content">
                    <p><strong>FC Points: +20% Bonus</strong></p>
                    <p>Preț: <span style="color: #00ff00; font-weight: bold;">9.99€</span></p>
                    <small style="color: #666;">Valabil: 02h : 45m</small>
                </div>
            </div>
        </aside>

        <main>
            <section class="product-card">
                <div class="product-header">
                    <h2><?php echo htmlspecialchars($main_product['name'] ?? 'EA SPORTS FC 26 - Antony GOAT Edition'); ?></h2>
                </div>

                <div class="product-details">
                    <div class="cover-section">
                        <img src="<?php echo htmlspecialchars($main_product['image_path'] ?? 'pngs/ea_fc26.webp'); ?>" alt="Cover" width="200" height="280">
                        <p><strong>EDIȚIE SPECIALĂ</strong></p>
                    </div>

                    <div style="flex: 1;">
                        <p><strong>Descriere:</strong><br>
                            <?php echo htmlspecialchars($main_product['description'] ?? 'Nicio descriere disponibilă.'); ?></p>
                        <p><strong>Platformă:</strong> <?php echo htmlspecialchars($main_product['platform'] ?? 'PS5, Xbox, PC'); ?></p>

                        <table class="stats-table" id="antonyStats">
                            <thead>
                                <tr style="background-color: #333333;">
                                    <th colspan="2" style="text-align: center; cursor: default;">Rating Star: Antony (The Spinner)</th>
                                </tr>
                                <tr>
                                    <th>Atribut</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Viteză (PAC):</td>
                                    <td>95</td>
                                </tr>
                                <tr>
                                    <td>Șut (SHO):</td>
                                    <td>92</td>
                                </tr>
                                <tr>
                                    <td>Dribling (DRI):</td>
                                    <td>99</td>
                                </tr>
                                <tr>
                                    <td>Spinning (SPN):</td>
                                    <td>100</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="pricing-section">
                    <table class="pricing-table">
                        <thead style="background-color: #333333;">
                            <tr>
                                <th>Pachet</th>
                                <th>Beneficii FC Points</th>
                                <th>Preț</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Standard</td>
                                <td>6000</td>
                                <td><?php echo htmlspecialchars($main_product['price'] ?? '69.99€'); ?></td>
                            </tr>
                            <tr>
                                <td>Ultimate Edition</td>
                                <td>12000</td>
                                <td><?php echo (isset($main_product['price']) ? (floatval($main_product['price']) + 50) : 119.99) . '€'; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <div class="admin-links">
                        <a href="Edit.php?id=1" style="color: #00ff00; text-decoration: none; padding-right: 10px;">Editează
                            Produs</a> |
                        <a href="Delete.php?id=1" style="color: #ff0000; text-decoration: none; padding-left: 10px;">Șterge</a>
                    </div>
                <?php endif; ?>
            </section>

            <div class="games-section">
                <h3>Alte Jocuri Disponibile</h3>
                <table class="games-table">
                    <thead>
                        <tr>
                            <th>Imagine</th>
                            <th>Nume Joc</th>
                            <th>Genul</th>
                            <th>Platformă</th>
                            <th>Preț (€)</th>
                            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <th>Acțiuni</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_products as $game): ?>
                            <tr>
                                <td>
                                    <img src="<?php echo htmlspecialchars($game['image_path'] ?: 'pngs/default.png'); ?>" alt="Game" class="game-image">
                                </td>
                                <td>
                                    <div class="game-info-container">
                                        <?php echo htmlspecialchars($game['name']); ?>
                                        <div class="game-info-tooltip">
                                            <strong><?php echo htmlspecialchars($game['name']); ?></strong><br>
                                            <?php echo htmlspecialchars($game['description'] ?? 'Nicio descriere disponibilă.'); ?>
                                        </div>
                                    </div>
                                </td>
                                <td><div class="game-info-container"><?php echo htmlspecialchars($game['category_name'] ?: 'Nespecificat'); ?></div></td>
                                <td><?php echo htmlspecialchars($game['platform'] ?: '-'); ?></td>
                                <td><?php echo htmlspecialchars($game['price'] ?: '-'); ?></td>
                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <td>
                                    <a href="Edit.php?id=<?php echo $game['id']; ?>" style="color: #00ff00; text-decoration: none;">Editează</a> | 
                                    <a href="Delete.php?id=<?php echo $game['id']; ?>" style="color: #ff0000; text-decoration: none;" onclick="return confirm('Sigur vrei să ștergi?')">Șterge</a>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="news-section">
                    <h3>Noutăți Magazin</h3>
                    <ul class="news-list" id="newsList">
                        <li class="news-item active">
                            <div class="news-text">Ediția FC 26 Antony aduce animații noi și 12,000 FC Points cadou!</div>
                            <a href="#" class="read-more">Citește mai mult</a>
                        </li>
                        <li class="news-item">
                            <div class="news-text">Oferta săptămânii: Pachetul "Tactical FPS" la doar 19.99€ pentru membrii Plus.</div>
                            <a href="#" class="read-more">Citește mai mult</a>
                        </li>
                        <li class="news-item">
                            <div class="news-text">Serverele pentru multiplayer online vor fi optimizate marți la ora 03:00.</div>
                            <a href="#" class="read-more">Citește mai mult</a>
                        </li>
                    </ul>
                    <div class="news-controls">
                        <button id="prevNews" class="news-btn">Previous</button>
                        <button id="nextNews" class="news-btn">Next</button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <footer>
        <div class="bottom-menu">
            <div class="item">Catalog
                <div class="submenu">
                    <a href="Store.php">Toate jocurile</a>
                </div>
            </div>
            <div class="item">Platforme
                <div class="submenu">
                    <a href="#">PS5</a>
                    <a href="#">Xbox</a>
                    <a href="#">PC</a>
                </div>
            </div>
            <div class="item">Suport
                <div class="submenu">
                    <a href="#">Contact</a>
                </div>
            </div>
        </div>
        <p>&copy; 2026 AleXteam</p>
    </footer>

    <script>
        function makeTableSortable(tableId) {
            const table = document.getElementById(tableId);
            if (!table) return;
            
            const headers = table.querySelectorAll('thead tr:last-child th');
            const tbody = table.querySelector('tbody');

            headers.forEach((header, index) => {
                header.addEventListener('click', () => {
                    const rows = Array.from(tbody.querySelectorAll('tr'));
                    const currentDir = header.getAttribute('data-dir') === 'asc' ? 'desc' : 'asc';
                    
                    rows.sort((a, b) => {
                        const valA = a.children[index].textContent.trim();
                        const valB = b.children[index].textContent.trim();
                        
                        const n1 = parseFloat(valA);
                        const n2 = parseFloat(valB);
                        
                        if (!isNaN(n1) && !isNaN(n2)) {
                            return currentDir === 'asc' ? n1 - n2 : n2 - n1;
                        }
                        return currentDir === 'asc' ? valA.localeCompare(valB) : valB.localeCompare(valA);
                    });

                    header.setAttribute('data-dir', currentDir);
                    tbody.append(...rows);
                });
            });
        }

        makeTableSortable('antonyStats');

        (function() {
            const newsItems = document.querySelectorAll('.news-item');
            const totalNews = newsItems.length;
            let currentIndex = 0;
            let intervalId;
            const nSeconds = 5; 

            function showItem(index) {
                if (totalNews <= 1) return;

                newsItems.forEach(item => item.classList.remove('active'));
                
                currentIndex = (index + totalNews) % totalNews;
                
                newsItems[currentIndex].classList.add('active');
            }

            function resetTimer() {
                clearInterval(intervalId);
                intervalId = setInterval(() => {
                    showItem(currentIndex + 1);
                }, nSeconds * 1000);
            }

            const btnNext = document.getElementById('nextNews');
            const btnPrev = document.getElementById('prevNews');

            if (btnNext && btnPrev) {
                btnNext.addEventListener('click', () => {
                    showItem(currentIndex + 1);
                    resetTimer();
                });

                btnPrev.addEventListener('click', () => {
                    showItem(currentIndex - 1);
                    resetTimer();
                });
            }

            if (totalNews > 1) {
                resetTimer();
            }
        })();
    </script>
</body>

</html>