<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulare de Validare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .form-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            gap: 30px;
            padding: 50px 20px;
            flex: 1;
        }

        .validation-card {
            background-color: #1a1a1a;
            border: 1px solid #333;
            padding: 30px;
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .validation-card h2 {
            color: var(--accent-green);
            margin-bottom: 20px;
            text-align: center;
        }

        .input-group {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .input-group input {
            flex: 1;
            padding: 12px;
            background-color: #2a2a2a;
            border: 1px solid #444;
            color: white;
            border-radius: 4px;
            outline: none;
        }

        .input-group input:focus {
            border-color: var(--accent-green);
        }

        .status-dot {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            background-color: #444;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .dot-valid { background-color: #00ff00 !important; box-shadow: 0 0 10px #00ff00; }
        .dot-invalid { background-color: #ff0000 !important; box-shadow: 0 0 10px #ff0000; }

        .toggle-btn {
            background: none;
            border: none;
            color: var(--accent-green);
            cursor: pointer;
            font-size: 1.2em;
            padding: 0;
            outline: none;
        }

        .rule-box {
            position: relative;
            margin-top: 10px;
            padding: 10px;
            text-align: center;
            overflow: hidden;
            border-radius: 4px;
        }

        .rule-band {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--accent-green);
            color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.8em;
            cursor: pointer;
            transition: transform 0.5s ease;
            z-index: 2;
        }

        .final-submit-container {
            width: 100%;
            max-width: 400px;
            margin-bottom: 50px;
        }

        .final-btn {
            width: 100%;
            padding: 15px;
            background-color: var(--accent-green);
            color: #000;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1.1em;
            cursor: pointer;
            text-transform: uppercase;
            transition: transform 0.2s, background-color 0.3s;
        }

        .final-btn:hover {
            background-color: #00cc00;
            transform: scale(1.02);
        }
    </style>
</head>
<body>
    <header>
        <h1>Validare Date</h1>
        <nav>
            <a href="Home.php">Acasă</a> 
        </nav>
    </header>

    <main class="form-wrapper">
        <div class="validation-card">
            <h2>Descoperă Regula</h2>
            <div class="input-group">
                <input type="text" id="textInput1" placeholder="Indiciu: 1">
                <div id="dot1" class="status-dot"></div>
            </div>
            <div class="input-group">
                <input type="text" id="textInput2" placeholder="Indiciu: alfabet">
                <div id="dot2" class="status-dot"></div>
            </div>
            
            <div class="rule-box">
                <div class="rule-band" id="revealBand">Apasă pentru a dezvălui regula</div>
                <p style="font-size: 0.8em; color: #666; margin: 0;">Regulă: caractere permise [a-z0-9]</p>
            </div>
        </div>

        <div class="validation-card">
            <h2>Descoperă Parola</h2>
            <div class="input-group">
                <button type="button" id="togglePassword" class="toggle-btn" title="Arată/Ascunde Parola">
                    <i class="fa-solid fa-eye" id="toggleIcon"></i>
                </button>
                <input type="password" id="passInput" placeholder="Indiciu: Are c3va din fiecare!">
                <div id="dot3" class="status-dot"></div>
            </div>
            
            <div class="rule-box">
                <div class="rule-band" id="revealBand2">Apasă pentru a dezvălui regula</div>
                <p style="font-size: 0.8em; color: #666; margin: 0;">Regulă: minim o literă mare, una mică, o cifră și "!"</p>
            </div>
        </div>

        <div class="validation-card">
            <h2>Descoperă Emailul</h2>
            <div class="input-group">
                <input type="text" id="emailInput" placeholder="Indiciu: format_standard_email">
                <div id="dot4" class="status-dot"></div>
            </div>
            
            <div class="rule-box">
                <div class="rule-band" id="revealBand3">Apasă pentru a dezvălui regula</div>
                <p style="font-size: 0.8em; color: #666; margin: 0;">Regulă: litere, cifre, _, un singur '@' și minim un '.'</p>
            </div>
        </div>

        <div class="validation-card">
            <h2>Descoperă Numărul de Telefon</h2>
            <div class="input-group">
                <input type="text" id="phoneInput" placeholder="Indiciu: România cu spații și paranteze">
                <div id="dot5" class="status-dot"></div>
            </div>
            
            <div class="rule-box">
                <div class="rule-band" id="revealBand4">Apasă pentru a dezvălui regula</div>
                <p style="font-size: 0.8em; color: #666; margin: 0;">Regulă: format (+40) 000 000 000</p>
            </div>
        </div>

        <div class="validation-card">
            <h2>Descoperă Data</h2>
            <div class="input-group">
                <select id="dateFormat">
                    <option value="zz/ll/aaaa">zz/ll/aaaa</option>
                    <option value="ll/zz/aaaa">ll/zz/aaaa</option>
                    <option value="zz/ll/aa">zz/ll/aa</option>
                </select>
                <input type="text" id="dateInput" placeholder="Indiciu: format selectat">
                <div id="dot6" class="status-dot"></div>
            </div>
            
            <div class="rule-box">
                <div class="rule-band" id="revealBand5">Apasă pentru a dezvălui regula</div>
                <p style="font-size: 0.8em; color: #666; margin: 0;">Regulă: dată calendaristică validă conform formatului ales</p>
            </div>
        </div>

        <div class="validation-card">
            <h2>Localizare Dinamică</h2>
            <div class="input-group" style="flex-direction: column; align-items: stretch;">
                <select id="judetSelect">
                    <option value="">Alege Județ...</option>
                </select>
                <select id="orasSelect">
                    <option value="">Alege Oraș...</option>
                </select>
            </div>
        </div>

        <div class="final-submit-container">
            <button id="finalSubmit" class="final-btn">Trimite Formularele</button>
        </div>
    </main>

    <script>
       
        function valideazaData(sir, format) {
            const parts = sir.split('/');
            if (parts.length !== 3) return false;

            let d, m, y;
            if (format === 'zz/ll/aaaa' || format === 'zz/ll/aa') {
                [d, m, y] = parts;
            } else if (format === 'll/zz/aaaa') {
                [m, d, y] = parts;
            }

            if ((format === 'zz/ll/aaaa' || format === 'll/zz/aaaa') && y.length !== 4) return false;
            if (format === 'zz/ll/aa' && y.length !== 2) return false;

            const yRaw = y;

            d = parseInt(d); m = parseInt(m); y = parseInt(y);
            if (isNaN(d) || isNaN(m) || isNaN(y)) return false;
            if (m < 1 || m > 12) return false;

            const fullYear = y < 100 ? 2000 + y : y;
            const daysInMonth = new Date(fullYear, m, 0).getDate();
            return d >= 1 && d <= daysInMonth;
        }

        function initValidation(inputId, dotId, regex) {
            const input = document.getElementById(inputId);
            const dot = document.getElementById(dotId);

            input.addEventListener('input', function() {
                const value = input.value;
                if (value === "") {
                    dot.className = "status-dot";
                } else {
                    
                    const isValid = (typeof regex === 'function') 
                        ? regex(value) 
                        : regex.test(value);
                        
                    dot.className = "status-dot " + (isValid ? "dot-valid" : "dot-invalid");
                }
            });
        }

        document.getElementById('revealBand').addEventListener('click', function() {
            this.style.transform = "translateY(-100%)";
        });

        document.getElementById('revealBand2').addEventListener('click', function() {
            this.style.transform = "translateY(-100%)";
        });

        document.getElementById('revealBand3').addEventListener('click', function() {
            this.style.transform = "translateY(-100%)";
        });

        document.getElementById('revealBand4').addEventListener('click', function() {
            this.style.transform = "translateY(-100%)";
        });

        document.getElementById('revealBand5').addEventListener('click', function() {
            this.style.transform = "translateY(-100%)";
        });

        document.getElementById('togglePassword').addEventListener('click', function() {
            const passInput = document.getElementById('passInput');
            const toggleIcon = document.getElementById('toggleIcon');
            const isPassword = passInput.type === 'password';
            
            passInput.type = isPassword ? 'text' : 'password';
            toggleIcon.classList.toggle('fa-eye', !isPassword);
            toggleIcon.classList.toggle('fa-eye-slash', isPassword);
        });

        const regexLitereCifre = /^[a-z0-9]+$/;
        
        const regexParola = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*!).+$/;

        const regexEmail = /^[a-zA-Z0-9_]+@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)+$/;

        const regexPhone = /^\(\+40\) \d{3} \d{3} \d{3}$/;

        initValidation('textInput1', 'dot1', regexLitereCifre);
        initValidation('textInput2', 'dot2', regexLitereCifre);
        initValidation('passInput', 'dot3', regexParola);
        initValidation('emailInput', 'dot4', regexEmail);
        initValidation('phoneInput', 'dot5', regexPhone);

        const dateSelect = document.getElementById('dateFormat');
        initValidation('dateInput', 'dot6', (val) => valideazaData(val, dateSelect.value));

        dateSelect.addEventListener('change', () => {
            document.getElementById('dateInput').dispatchEvent(new Event('input'));
        });

        const dateLocatii = {
            "București": ["Bragadiru", "Buftea", "Chitila", "Pantelimon", "Popești-Leordeni", "Voluntari "],
            "Cluj": ["Cluj-Napoca", "Turda", "Dej", "Gherla", "Huedin"],
            "Iași": ["Iași", "Pașcani", "Hârlău", "Târgu Frumos", "Podu Iloaiei"],
            "Timiș": ["Timișoara", "Lugoj", "Sânnicolau Mare", "Jimbolia", "Buziaș"],
            "Bacău": ["Bacău", "Onești", "Moinești", "Comănești", "Buhuși"]
        };

        const judetSelect = document.getElementById('judetSelect');
        const orasSelect = document.getElementById('orasSelect');

        for (let judet in dateLocatii) {
            let opt = document.createElement('option');
            opt.value = judet;
            opt.innerHTML = judet;
            judetSelect.appendChild(opt);
        }

        judetSelect.addEventListener('change', function() {
            
            orasSelect.innerHTML = '<option value="">Alege Oraș...</option>';
            
            const orase = dateLocatii[this.value];
            if (orase) {
                orase.forEach(oras => {
                    let opt = document.createElement('option');
                    opt.value = oras;
                    opt.innerHTML = oras;
                    orasSelect.appendChild(opt);
                });
            }
        });

        document.getElementById('finalSubmit').addEventListener('click', function() {
            const dotIds = ['dot1', 'dot2', 'dot3', 'dot4', 'dot5', 'dot6'];
            let allValid = true;

            dotIds.forEach(id => {
                const dot = document.getElementById(id);
                if (!dot.classList.contains('dot-valid')) {
                    allValid = false;
                }
            });

            if (allValid) {
                alert('Felicitări!');
            } else {
                alert('Mai încearcă!');
            }
        });
    </script>

    <footer>
        <p>&copy; 2026 AleXteam</p>
    </footer>
</body>
</html>