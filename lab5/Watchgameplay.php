<?php
session_start();
include 'db.php';

$stmt = $pdo->query("SELECT id, name, price FROM products WHERE price > 0 LIMIT 6"); 
$db_games = $stmt->fetchAll(); 
foreach ($db_games as &$game) { 
    $game['price'] = (float)$game['price'];
}
unset($game); 
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watch Gameplay - EA FC 26</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #0a0a0a;
            color: white;
            font-family: Arial, sans-serif;
        }

        .config-panel {
            background: #1a1a1a;
            padding: 20px;
            margin: 20px auto;
            border: 1px solid #333;
            border-radius: 8px;
            display: flex;
            gap: 20px;
            justify-content: center;
            align-items: center;
            max-width: 600px;
        }

        .config-panel input {
            padding: 8px;
            background: #2a2a2a;
            border: 1px solid #00ff00;
            color: white;
            width: 60px;
            text-align: center;
        }

        .content-layout {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 10px 20px;
        }

        .slider-wrapper {
            position: relative;
            width: 800px; 
            border: 4px solid #333;
            box-shadow: 0 0 30px rgba(0,0,0,0.5);
            overflow: hidden;
            background: #000;
            border-radius: 8px;
            cursor: pointer; 
            user-select: none; 
        }

        /* Container pentru Tableta si Maini */
        .tablet-device {
            position: relative;
            padding: 20px 40px; 
            background: #222; 
            border-radius: 25px;
            border: 4px solid #444;
            box-shadow: 0 20px 50px rgba(0,0,0,0.7);
            transition: transform 0.1s ease-out;
            transform-style: preserve-3d;
        }

        /* Mâinile care țin tableta */
        .hand {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 120px;
            color: #d2b48c; 
            z-index: 20;
            text-shadow: 5px 5px 15px rgba(0,0,0,0.5);
            pointer-events: none;
            transition: transform 0.1s ease-out;
        }

        .left-hand {
            left: -60px;
            transform: translateY(-50%) rotate(10deg);
        }

        .right-hand {
            right: -60px;
            transform: translateY(-50%) rotate(-10deg) scaleX(-1);
        }

        .slider-viewport {
            width: 100%;
            overflow: hidden;
            position: relative;
        }

        .slider-content {
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .slider-item, .slider-content img {
            width: 100%;
            height: 450px; 
            object-fit: contain; 
            display: block;
            border: none;
            background-color: #000; 
        }

        .video-slide {
            width: 100%;
            height: 450px;
            background: #000;
        }

        .nav-arrow {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 40px;
            background: rgba(0, 255, 0, 0.4);
            display: flex;
            z-index: 10;
            justify-content: center;
            align-items: center;
            color: white;
            cursor: pointer;
            font-size: 24px;
            opacity: 0; 
            transition: all 0.3s ease;
        }

        .up-arrow { 
            top: 10px; 
            border-radius: 0 0 40px 40px;
        }
        .down-arrow { 
            bottom: 10px; 
            border-radius: 40px 40px 0 0;
        }

        .nav-arrow:hover { 
            background: rgba(0, 255, 0, 0.8);
            height: 50px;
            color: black;
        }

        .slider-wrapper:hover .nav-arrow { 
            opacity: 1; 
        }

        iframe, video {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Stiluri Cautare Live */
        .search-section {
            margin: 20px auto 40px auto;
            width: 100%;
            max-width: 500px;
            text-align: center;
            background: #111;
            padding: 40px;
            border: 1px solid #333;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.8);
        }

        .search-section h3 {
            color: #00ff00;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 0;
            margin-bottom: 30px;
            font-size: 1.4em;
            border-bottom: 1px solid #222;
            padding-bottom: 15px;
        }

        .search-container {
            position: relative;
            margin-top: 15px;
        }

        #gameSearch {
            width: 100%;
            padding: 14px 18px;
            background-color: #0a0a0a;
            border: 1px solid #444;
            color: white;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        #gameSearch:focus {
            border-color: #00ff00;
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.1);
            background-color: #000;
        }

        #gameList {
            list-style: none;
            padding: 0;
            margin: 8px 0 0 0;
            background: #161616;
            border: 1px solid #333;
            border-radius: 8px;
            max-height: 250px;
            overflow-y: auto;
            display: none; 
            position: absolute;
            width: 100%;
            z-index: 100;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        }

        #gameList li {
            padding: 12px 20px;
            text-align: left;
            border-bottom: 1px solid #1f1f1f;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.95em;
            color: #ccc;
        }

        #gameList li:last-child {
            border-bottom: none;
        }

        #gameList li:hover {
            background-color: #1a1a1a;
            color: #00ff00;
            padding-left: 25px;
        }

        /* Stiluri Vitezometru si Cos */
        .shop-section {
            margin: 40px auto;
            width: 100%;
            max-width: 800px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #111;
            padding: 30px;
            border: 1px solid #333;
            border-radius: 12px;
        }

        .gauge-container {
            position: relative;
            width: 300px;
            height: 150px;
            margin-bottom: 50px;
        }

        .gauge-svg {
            width: 100%;
            height: 100%;
        }

        .gauge-bg {
            fill: none;
            stroke: #333;
            stroke-width: 20;
        }

        .gauge-fill {
            fill: none;
            stroke: #00ff00;
            stroke-width: 20;
            stroke-dasharray: 251.3; 
            stroke-dashoffset: 251.3; 
            transition: stroke-dashoffset 0.5s ease-out;
            stroke-linecap: round;
        }

        #gaugeNeedle {
            stroke: #ff0000;
            stroke-width: 4;
            stroke-linecap: round;
            transform-origin: 100px 90px;
            transform: rotate(0deg);
            transition: transform 0.5s ease-out;
        }

        .gauge-text {
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
        }

        .gauge-value {
            font-size: 2em;
            font-weight: bold;
            color: #00ff00;
            display: block;
        }

        .cart-container {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 30px;
        }

        .game-card {
            background: #1a1a1a;
            padding: 15px;
            border: 1px solid #333;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s;
        }

        .game-card:hover {
            border-color: #00ff00;
        }

        .game-card label {
            cursor: pointer;
            flex-grow: 1;
        }

        .price-tag {
            color: #00ff00;
            font-weight: bold;
            margin-right: 10px;
        }

        .discount-msg {
            margin-top: 20px;
            font-weight: bold;
            color: #ff9900;
            display: none;
        }

        .discount-active {
            color: #00ff00 !important;
            text-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }

        .confetti {
            position: fixed;
            width: 8px;
            height: 8px;
            background-color: #00ff00;
            pointer-events: none;
            z-index: 9999;
            border-radius: 2px;
            box-shadow: 0 0 5px #00ff00;
        }

        /* Stil pentru degetul care face swipe */
        .swipe-finger {
            position: absolute;
            right: 50px;
            font-size: 60px;
            color: rgba(210, 180, 140, 0.9);
            z-index: 100;
            pointer-events: none;
            text-shadow: 0 0 15px rgba(0,0,0,0.8);
        }
    </style>
</head>
<body>
    <header>
        <h1>EA FC 26 - Gameplay Area</h1>
        <nav><a href="Home.php">Înapoi la Acasă</a></nav>
    </header>

    <?php if(isset($_SESSION['user_id'])): ?>
    <div class="user-profile-widget">
        <a href="Profile.php">
            <img src="<?php echo htmlspecialchars($_SESSION['profile_image'] ?? 'pngs/default_user.jpg'); ?>" alt="Profile">
            <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </a>
    </div>
    <?php endif; ?>

    <main>
        <div class="config-panel">
            <div>
                <label>Imagini vizibile:</label>
                <input type="number" id="imgCount" value="1" min="1" max="5">
            </div>
            <div>
                <label>Secunde interval:</label>
                <input type="number" id="secInterval" value="3" min="1">
            </div>
            <button id="applySettings" class="news-btn">Aplică</button>
            <button id="stopSlider" class="news-btn" style="background-color: #ff0000; color: white;">Stop</button>
        </div>

        <div class="content-layout">
            <div class="tablet-device">
                <i class="fas fa-hand-back-fist hand left-hand"></i>
                <i class="fas fa-hand-back-fist hand right-hand"></i>
                
                <div class="slider-wrapper">
                    <div class="nav-arrow up-arrow"><i class="fas fa-chevron-up"></i></div>
                    <div class="slider-viewport">
                        <div class="slider-content">
                            <img src="pngs/ea_fc26.webp" alt="Game 1">
                            <img src="pngs/csgo.png" alt="Game 2">
                            <img src="pngs/rocket.png" alt="Game 3">
                            <img src="pngs/valorant.png" alt="Game 4">
                            <div class="video-slide">
                                <video controls>
                                    <source src="pngs/cs2_4k.mp4" type="video/mp4">
                                </video>
                            </div>
                        </div>
                    </div>
                    <div class="nav-arrow down-arrow"><i class="fas fa-chevron-down"></i></div>
                </div>
            </div>
        </div>

        <!-- Sectiune Cautare Live -->
        <div class="search-section">
            <h3>Jocuri Disponibile</h3>
            <div class="search-container">
                <input type="text" id="gameSearch" placeholder="Apasă aici pentru a vedea jocurile sau caută...">
                <ul id="gameList">
                    <?php foreach ($db_games as $game): ?>
                        <li data-id="<?php echo $game['id']; ?>"><i class="fas fa-search"></i> <?php echo htmlspecialchars($game['name']); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Sectiune Cos si Vitezometru -->
        <div class="shop-section">
            <div class="gauge-container">
                <svg class="gauge-svg" viewBox="0 0 200 100">
                    <path class="gauge-bg" d="M20,90 A80,80 0 0,1 180,90" />
                    <path id="gaugePath" class="gauge-fill" d="M20,90 A80,80 0 0,1 180,90" />
                    <line id="gaugeNeedle" x1="100" y1="90" x2="30" y2="90" />
                    <circle cx="100" cy="90" r="5" fill="#fff" />
                </svg>
                <div class="gauge-text">
                    <span class="gauge-value"><span id="totalPrice">0</span>€</span>
                    <small>Target: 50€ pentru -10%</small>
                </div>
            </div>

            <div id="discountAlert" class="discount-msg">🎉 Felicitări! Ai obținut 10% reducere!</div>

            <div class="cart-container">
                <?php foreach ($db_games as $game): ?>
                    <div class="game-card">
                        <label>
                            <input type="checkbox" class="game-item" data-id="<?php echo $game['id']; ?>" data-name="<?php echo htmlspecialchars($game['name']); ?>" data-price="<?php echo $game['price']; ?>"> 
                            <?php echo htmlspecialchars($game['name']); ?>
                        </label>
                        <span class="price-tag"><?php echo $game['price']; ?>€</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <script>
        $(document).ready(function() {
            let intervalId;
            let isAutoPlaying = true;
            const slideHeight = 450; 
            let startY = 0;
            let isDragging = false;

            // Funcție pentru animația mâinii drepte
            function animateRightHandSwipe(direction) {
                const $finger = $('<i class="fas fa-hand-pointer swipe-finger"></i>');
                $('.tablet-device').append($finger);

                const startPos = direction === 'up' ? 400 : 100;
                const endPos = direction === 'up' ? 100 : 400;

                $finger.css({ top: startPos, opacity: 0, transform: 'scaleX(-1) rotate(45deg)' });
                
                $('.right-hand').animate({ marginTop: direction === 'up' ? '-20px' : '20px' }, 200)
                                .animate({ marginTop: '0px' }, 300);

                $finger.animate({ opacity: 1 }, 100)
                       .animate({ top: endPos }, 400)
                       .animate({ opacity: 0 }, 100, function() {
                           $(this).remove();
                       });
            }

            // Efect 3D Tilt pe ansamblul Tableta + Maini
            $('.tablet-device').on('mousemove', function(e) {
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const rotateY = ((x - centerX) / centerX) * 10;
                const rotateX = ((centerY - y) / centerY) * 10;
                
                $(this).css('transform', `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`);
            }).on('mouseleave', function() {
                $(this).css('transform', 'perspective(1000px) rotateX(0deg) rotateY(0deg)');
            });

            // Functie de Confetti Neon
            function launchConfetti() {
                const colors = ['#00ff00', '#00ffff', '#ffffff', '#ff00ff'];
                for (let i = 0; i < 50; i++) {
                    const color = colors[Math.floor(Math.random() * colors.length)];
                    const $p = $('<div class="confetti"></div>').css({
                        left: '50%',
                        top: '80%',
                        backgroundColor: color,
                        boxShadow: `0 0 10px ${color}`
                    }).appendTo('body');

                    const destX = (Math.random() - 0.5) * 1000;
                    const destY = (Math.random() - 0.5) * 1000;

                    $p.animate({
                        left: `+=${destX}px`,
                        top: `+=${destY}px`,
                        opacity: 0
                    }, 1000 + Math.random() * 1000, function() {
                        $(this).remove();
                    });
                }
            }

            function updateSlider() {
                clearInterval(intervalId);
                
                const count = parseInt($('#imgCount').val()) || 1;
                
                $('.slider-viewport').height(count * slideHeight);
                
                if (!isAutoPlaying) return;

                const seconds = parseInt($('#secInterval').val()) || 3;
                intervalId = setInterval(slideUp, seconds * 1000);
            }

            function slideUp() {
                const $content = $('.slider-content');
                const $firstChild = $content.children().first();

                $firstChild.animate({ marginTop: -slideHeight }, 600, function() {
                    $(this).detach().css('marginTop', 0).appendTo($content);
                });
            }

            function slideDown() {
                const $content = $('.slider-content');
                const $lastChild = $content.children().last();

                $lastChild.detach().css('marginTop', -slideHeight).prependTo($content);
                $lastChild.animate({ marginTop: 0 }, 600);
            }

            $('.down-arrow').on('click', function() {
                animateRightHandSwipe('up');
                slideUp();
                if (isAutoPlaying) updateSlider(); 
            });

            $('.up-arrow').on('click', function() {
                animateRightHandSwipe('down'); 
                slideDown();
                if (isAutoPlaying) updateSlider();
            });

            $('#applySettings').on('click', function() {
                isAutoPlaying = true;
                updateSlider();
            });

            $('#stopSlider').on('click', function() {
                isAutoPlaying = false;
                clearInterval(intervalId);
            });

            $('#gameSearch').on('focus click', function() {
                $('#gameList').slideDown(200);
            });

            let searchTimeout;
            $('#gameSearch').on('keyup', function() {
                const searchTerm = $(this).val();
                clearTimeout(searchTimeout);

                searchTimeout = setTimeout(() => {
                    if (searchTerm.length < 1) {
                        $('#gameList').hide();
                        return;
                    }

                    $.ajax({
                        url: 'Search_games.php',
                        method: 'POST', 
                        data: { term: searchTerm },
                        dataType: 'json',
                        success: function(results) {
                            const $gameList = $('#gameList');
                            $gameList.empty();

                            if (Array.isArray(results) && results.length > 0) {
                                results.forEach(game => {
                                    $gameList.append(`<li data-id="${game.id}" data-name="${game.name}" data-price="${game.price}"><i class="fas fa-search"></i> ${game.name}</li>`);
                                });
                                $gameList.slideDown(200);
                            } else if (results.error) {
                                $gameList.append(`<li style="color: #ff4444;">Eroare: ${results.error}</li>`).show();
                            } else {
                                $gameList.append(`<li><i class="fas fa-exclamation-circle"></i> Niciun joc găsit în DB</li>`).show();
                            }
                        }
                    });
                }, 300);
            });

            $(document).on('click', '#gameList li', function() {
                const gameId = $(this).data('id');
                const gameName = $(this).data('name');
                const gamePrice = $(this).data('price');

                if (!gameId) return;

                let $checkbox = $(`.game-item`).filter(function() { return $(this).data('id') == gameId; });

                if ($checkbox.length === 0) {
                    const newCard = `
                        <div class="game-card">
                            <label>
                                <input type="checkbox" class="game-item" data-id="${gameId}" data-name="${gameName}" data-price="${gamePrice}"> 
                                ${gameName}
                            </label>
                            <span class="price-tag">${gamePrice}€</span>
                        </div>`;
                    $('.cart-container').append(newCard);
                    $checkbox = $(`.game-item`).filter(function() { return $(this).data('id') == gameId; });
                }

                if (!$checkbox.is(':checked')) {
                    $checkbox.prop('checked', true).trigger('change');
                }

                $('#gameList').slideUp(200); 
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('.search-container').length) {
                    $('#gameList').slideUp(200);
                }
            });

            $('.slider-wrapper').on('mousedown', function(e) {
                e.preventDefault();
                isDragging = true;
                startY = e.pageY;
                $(this).css('cursor', 'grabbing');
            });

            $(window).on('mouseup', function() {
                if (isDragging) {
                    isDragging = false;
                    $('.slider-wrapper').css('cursor', 'pointer');
                }
            });

            $('.slider-wrapper').on('mousemove', function(e) {
                if (!isDragging) return;

                const currentY = e.pageY;
                const diff = startY - currentY;

                if (Math.abs(diff) > 50) {
                    if (diff > 0) {
                        
                        $('.down-arrow').click();
                    } else {
                        
                        $('.up-arrow').click();
                    }
                    isDragging = false; 
                }
            });

            $('.slider-wrapper').on('wheel', function(e) {
                e.preventDefault(); 
                
                if (e.originalEvent.deltaY > 0) {
                    
                    $('.down-arrow').click();
                } else {
                    
                    $('.up-arrow').click();
                }
            });

            function updateCartDisplay() {
                let total = 0;
                $('.game-item:checked').each(function() {
                    total += parseFloat($(this).data('price'));
                });

                $('#totalPrice').text(total.toFixed(2));

                const percentage = Math.min(total / 50, 1);
                const maxDash = 251.3;
                const newOffset = maxDash - (percentage * maxDash);
                
                $('#gaugePath').css('stroke-dashoffset', newOffset);

                const rotation = percentage * 180;
                $('#gaugeNeedle').css('transform', `rotate(${rotation}deg)`);

                if (total >= 50) {
                    if (!$('#discountAlert').is(':visible')) {
                        launchConfetti(); 
                        $('#discountAlert').fadeIn().addClass('discount-active');
                        $('.gauge-value').addClass('discount-active');
                        $('.gauge-container').animate({ left: '+=5px' }, 50).animate({ left: '-=10px' }, 50).animate({ left: '+=5px' }, 50);
                    }
                } else {
                    $('#discountAlert').fadeOut().removeClass('discount-active');
                    $('.gauge-value').removeClass('discount-active');
                }
            }

            $(document).on('change', '.game-item', function() {
                const $this = $(this);
                const gameId = $this.data('id');
                const gameName = $this.data('name');
                const gamePrice = $this.data('price');
                const action = $this.is(':checked') ? 'add' : 'remove';

                $.ajax({
                    url: 'Update_cart.php',
                    method: 'POST',
                    data: {
                        id: gameId,
                        name: gameName,
                        price: gamePrice,
                        action: action
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            console.log('Coș actualizat:', response.cart);
                            updateCartDisplay(); 
                        } else {
                            console.error('Eroare la actualizarea coșului:', response.message);
                            $this.prop('checked', !$this.is(':checked')); 
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error la coș: ", status, error);
                        $this.prop('checked', !$this.is(':checked')); 
                    }
                });
            });

            $.ajax({
                url: 'Update_cart.php', 
                method: 'POST', 
                data: { action: 'get_cart' }, 
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.cart) {
                        Object.keys(response.cart).forEach(function(gameId) {
                            $(`.game-item[data-id="${gameId}"]`).prop('checked', true);
                        });
                        updateCartDisplay();
                    }
                }
            });

            updateSlider(); 
            updateCartDisplay(); 
        });
    </script>
</body>
</html>