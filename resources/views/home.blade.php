<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Roulette Game - Spin the Wheel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            padding: 2rem;
            box-sizing: border-box;
        }
        
        .main-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 4rem);
            padding: 2rem 0;
            overflow-x: hidden;
            overflow-y: hidden;
            position: relative;
        }
        
        .game-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0 2rem;
            position: relative;
            max-width: 600px;
            width: 100%;
        }
        
        .player-section {
            position: absolute;
            top: 2rem;
            right: 2rem;
            width: 300px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            transition: transform 0.3s ease;
            max-height: 80vh;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 10;
        }
        
        .player-section.collapsed {
            transform: translateX(100%);
            opacity: 0;
            pointer-events: none;
        }
        
        .toggle-btn {
            background: #dc3545;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            color: white;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            flex-shrink: 0;
        }
        
        .show-toggle-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
        }
        
        .show-toggle-btn.visible {
            opacity: 1;
            visibility: visible;
        }
        
        .toggle-btn:hover {
            background: #c82333;
            transform: scale(1.1);
        }
        
        .show-toggle-btn:hover {
            background: #218838;
            transform: scale(1.1);
        }
        
        .player-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e9ecef;
        }
        
        .player-section h3 {
            color: #333;
            margin: 0;
            font-weight: bold;
        }
        
        .player-input-area {
            padding: 1rem 0;
        }
        
        .player-input-area textarea {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 1rem;
            transition: border-color 0.3s ease;
            font-size: 0.95rem;
            line-height: 1.5;
        }
        
        .player-input-area textarea:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
            outline: none;
        }
        
        .player-input-area textarea::placeholder {
            color: #6c757d;
            font-style: italic;
        }
        
        .wheel-container {
            position: relative;
            width: 500px;
            height: 500px;
            margin: 2rem auto;
            min-width: 400px;
            min-height: 400px;
        }
        
        .roulette-wheel {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            position: relative;
            border: 6px solid #2c3e50;
            box-shadow: 
                0 0 20px rgba(0,0,0,0.3),
                inset 0 0 20px rgba(0,0,0,0.1);
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
            transition: transform 4s cubic-bezier(0.23, 1, 0.320, 1);
        }
        
        .roulette-wheel.spinning {
            animation: spin 4s cubic-bezier(0.23, 1, 0.320, 1);
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(var(--spin-rotation, 1800deg)); }
        }
        
        .wheel-section {
            position: absolute;
            width: 50%;
            height: 50%;
            transform-origin: 50% 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
            text-align: center;
            line-height: 1.2;
            overflow: hidden;
            z-index: 10;
            padding: 8px;
            box-sizing: border-box;
            border: 2px solid rgba(255,255,255,0.3);
        }
        
        .wheel-section .section-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 75%;
            height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: inherit;
            font-weight: inherit;
            color: inherit;
            text-shadow: inherit;
            line-height: 1.1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .wheel-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            z-index: 20;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .pointer {
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            border-top: 40px solid #e74c3c;
            z-index: 25;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
        }
        
        .pointer::before {
            content: '';
            position: absolute;
            top: -45px;
            left: -15px;
            width: 0;
            height: 0;
            border-left: 15px solid transparent;
            border-right: 15px solid transparent;
            border-top: 15px solid #c0392b;
        }
        
        .spin-button {
            background: linear-gradient(45deg, #dc3545, #e83e8c);
            border: none;
            border-radius: 50px;
            padding: 1rem 3rem;
            font-size: 1.3rem;
            font-weight: bold;
            color: white;
            transition: all 0.3s ease;
            margin: 2rem 0;
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }
        
        .spin-button:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(220, 53, 69, 0.4);
        }
        
        .spin-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        /* Winner Popup Overlay */
        .winner-popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .winner-popup-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        
        .winner-announcement {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 30px;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 30px 60px rgba(0,0,0,0.3);
            border: 3px solid #fff;
            position: relative;
            max-width: 500px;
            width: 90%;
            transform: scale(0.5) rotateY(180deg);
            transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            overflow: hidden;
        }
        
        .winner-announcement.show {
            transform: scale(1) rotateY(0deg);
        }
        
        .winner-announcement::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        .winner-name {
            font-size: 3rem;
            font-weight: bold;
            color: #fff;
            margin-bottom: 1rem;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.3);
            animation: bounceIn 0.8s ease-out 0.3s both;
        }
        
        @keyframes bounceIn {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }
            50% {
                transform: scale(1.1);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .winner-number {
            font-size: 1.4rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 1.5rem;
            animation: slideInUp 0.6s ease-out 0.5s both;
        }
        
        @keyframes slideInUp {
            0% {
                transform: translateY(30px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .congratulations-text {
            font-size: 1.2rem;
            color: #fff;
            margin-bottom: 2rem;
            animation: fadeIn 0.8s ease-out 0.7s both;
        }
        
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        
        .popup-close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255,255,255,0.2);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .popup-close-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: scale(1.1);
        }
        
        .celebration-icons {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }
        
        .celebration-icon {
            position: absolute;
            font-size: 2rem;
            color: #ffd700;
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }
        
        .celebration-icon:nth-child(1) { top: 10%; left: 10%; animation-delay: 0s; }
        .celebration-icon:nth-child(2) { top: 20%; right: 15%; animation-delay: 0.5s; }
        .celebration-icon:nth-child(3) { bottom: 20%; left: 20%; animation-delay: 1s; }
        .celebration-icon:nth-child(4) { bottom: 10%; right: 10%; animation-delay: 1.5s; }
        .celebration-icon:nth-child(5) { top: 50%; left: 5%; animation-delay: 2s; }
        .celebration-icon:nth-child(6) { top: 50%; right: 5%; animation-delay: 2.5s; }
        
        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            background: #ffd700;
            animation: confetti-fall 3s linear infinite;
        }
        
        @keyframes confetti-fall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-secondary {
            background: linear-gradient(45deg, #6c757d, #495057);
            border: none;
            border-radius: 50px;
            padding: 0.8rem 2rem;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
            color: white;
        }
        
        .game-title {
            color: white;
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .game-title h1 {
            font-size: 3rem;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            margin-bottom: 0.5rem;
        }
        
        .game-title p {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        
        .game-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .empty-wheel-message {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 15;
            color: #6c757d;
            text-align: center;
            pointer-events: none;
        }
        
        .empty-message-text {
            font-size: 1.2rem;
            font-weight: 500;
            line-height: 1.4;
        }
        
        .empty-message-text i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            opacity: 0.7;
        }
        
        .empty-message-text small {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }
            
            .main-container {
                flex-direction: column;
                overflow-x: hidden;
                min-height: calc(100vh - 2rem);
                justify-content: flex-start;
                align-items: center;
            }
            
            .player-section {
                position: fixed;
                top: 1rem;
                right: 1rem;
                left: 1rem;
                width: auto;
                z-index: 1000;
            }
            
            .player-section.collapsed {
                transform: translateY(-100%);
                opacity: 0;
                pointer-events: none;
            }
            
            .toggle-btn {
                top: 15px;
                right: 15px;
            }
            
            .wheel-container {
                width: 350px;
                height: 350px;
            }
            
            .winner-name {
                font-size: 2rem;
            }
            
            .spin-button {
                padding: 0.8rem 2rem;
                font-size: 1.1rem;
            }
            
            .game-content {
                max-width: 100%;
                padding: 0 1rem;
            }
            
            .winner-announcement {
                padding: 2rem;
                max-width: 90%;
            }
            
            .winner-name {
                font-size: 2rem;
            }
            
            .winner-number {
                font-size: 1.1rem;
            }
            
            .congratulations-text {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Show Toggle Button (appears when list is hidden) -->
        <button class="show-toggle-btn" id="showToggleBtn" onclick="showPlayerList()">
            <i class="fas fa-chevron-left"></i>
        </button>
        
        <!-- Player Input Section -->
        <div class="player-section" id="playerSection">
            <div class="player-section-header">
                <button class="toggle-btn" onclick="hidePlayerList()">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <h3><i class="fas fa-users"></i> Players</h3>
            </div>
            
            <div class="player-input-area">
                <textarea 
                    class="form-control" 
                    id="playersTextarea" 
                    rows="10" 
                    placeholder="Enter player names"
                    style="resize: vertical; min-height: 500px;"
                ></textarea>
            </div>
        </div>
        
        <!-- Game Section -->
        <div class="game-section">
            <div class="game-content">
                <div class="game-title">
                    <h1><i class="fas fa-dice"></i> Roulette Game</h1>
                </div>
                
                
                <!-- Roulette Wheel (always visible now) -->
                <div class="wheel-container" id="wheelContainer">
                    <div class="pointer"></div>
                    <div class="roulette-wheel" id="rouletteWheel">
                        <div class="wheel-center">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="empty-wheel-message" id="emptyWheelMessage">
                            <div class="empty-message-text">
                                <i class="fas fa-plus-circle"></i><br>
                                Add players to start spinning!<br>
                                <small>Enter names in the textarea</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button class="btn spin-button" id="spinButton" onclick="spinWheel()" disabled>
                    <i class="fas fa-play"></i> Spin the Wheel
                </button>
                
                <!-- Winner Popup Overlay -->
                <div class="winner-popup-overlay" id="winnerPopupOverlay">
                    <div class="winner-announcement" id="winnerAnnouncement">
                        <button class="popup-close-btn" onclick="closeWinnerPopup()">
                            <i class="fas fa-times"></i>
                        </button>
                        
                        <div class="celebration-icons">
                            <div class="celebration-icon">🎉</div>
                            <div class="celebration-icon">🏆</div>
                            <div class="celebration-icon">⭐</div>
                            <div class="celebration-icon">🎊</div>
                            <div class="celebration-icon">🎈</div>
                            <div class="celebration-icon">🎁</div>
                        </div>
                        
                        <div class="winner-name" id="winnerName"></div>
                        <div class="winner-number" id="winnerNumber"></div>
                        <div class="congratulations-text">🎉 Congratulations! 🎉</div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let isSpinning = false;
        let wheelSections = [];
        let playerListVisible = true; // Default to visible
        let players = [];
        
        // Hide player list
        function hidePlayerList() {
            const playerSection = document.getElementById('playerSection');
            const showToggleBtn = document.getElementById('showToggleBtn');
            
            playerListVisible = false;
            playerSection.classList.add('collapsed');
            showToggleBtn.classList.add('visible');
        }
        
        // Show player list
        function showPlayerList() {
            const playerSection = document.getElementById('playerSection');
            const showToggleBtn = document.getElementById('showToggleBtn');
            
            playerListVisible = true;
            playerSection.classList.remove('collapsed');
            showToggleBtn.classList.remove('visible');
        }
        
        // Debounce timer for auto-updating
        let updateTimeout;
        
        // Auto-update players from textarea
        function autoUpdatePlayers() {
            const textarea = document.getElementById('playersTextarea');
            const playerNames = textarea.value.trim();
            
            // Clear existing timeout
            clearTimeout(updateTimeout);
            
            // Set new timeout for debounced update
            updateTimeout = setTimeout(() => {
                if (!playerNames) {
                    // Clear players if textarea is empty
                    players = [];
                    updateRouletteWheel();
                    hideGameControls();
                    return;
                }
                
                // Parse player names from textarea (split by newlines and filter empty lines)
                const newPlayers = playerNames.split('\n')
                    .map(name => name.trim())
                    .filter(name => name.length > 0);
                
                if (newPlayers.length < 2) {
                    // Don't show game controls if less than 2 players
                    players = newPlayers;
                    updateRouletteWheel();
                    hideGameControls();
                    return;
                }
                
                // Accept duplicate entries - keep all players as entered
                players = newPlayers;
                
                // Update roulette wheel and show game controls
                updateRouletteWheel();
                showGameControls();
            }, 500); // 500ms delay to avoid too frequent updates
        }
        
        // Show game controls
        function showGameControls() {
            document.getElementById('spinButton').disabled = false;
            document.getElementById('spinButton').innerHTML = '<i class="fas fa-play"></i> Spin the Wheel';
            document.getElementById('actionButtons').style.display = 'flex';
            document.getElementById('emptyWheelMessage').style.display = 'none';
        }
        
        // Hide game controls
        function hideGameControls() {
            document.getElementById('spinButton').disabled = true;
            document.getElementById('spinButton').innerHTML = '<i class="fas fa-play"></i> Spin the Wheel';
            document.getElementById('actionButtons').style.display = 'none';
            document.getElementById('emptyWheelMessage').style.display = 'block';
        }
        
        // Reset game
        function resetGame() {
            players = [];
            updateRouletteWheel();
            hideGameControls();
            closeWinnerPopup();
            document.getElementById('playersTextarea').value = '';
        }
        
        // Calculate text rotation to keep names upright
        function calculateTextRotation(sectionIndex, totalSections) {
            const sectionAngle = (sectionIndex * 360) / totalSections;
            const textAngle = -sectionAngle; // Counter-rotate to keep text upright
            return textAngle;
        }
        
        // Create SVG path for pie slice
        function createPieSlicePath(centerX, centerY, radius, startAngle, endAngle) {
            const start = polarToCartesian(centerX, centerY, radius, endAngle);
            const end = polarToCartesian(centerX, centerY, radius, startAngle);
            const largeArcFlag = endAngle - startAngle <= 180 ? "0" : "1";
            return [
                "M", centerX, centerY,
                "L", start.x, start.y,
                "A", radius, radius, 0, largeArcFlag, 0, end.x, end.y,
                "Z"
            ].join(" ");
        }
        
        // Convert polar coordinates to cartesian
        function polarToCartesian(centerX, centerY, radius, angleInDegrees) {
            const angleInRadians = (angleInDegrees - 90) * Math.PI / 180.0;
            return {
                x: centerX + (radius * Math.cos(angleInRadians)),
                y: centerY + (radius * Math.sin(angleInRadians))
            };
        }
        
        // Update roulette wheel with current players
        function updateRouletteWheel() {
            const wheel = document.getElementById('rouletteWheel');
            const totalSections = players.length;
            
            if (!wheel) {
                return;
            }
            
            // Clear any existing sections
            wheel.innerHTML = '<div class="wheel-center"><i class="fas fa-star"></i></div>';
            wheelSections = [];
            
            // If no players, show empty wheel message
            if (totalSections === 0) {
                wheel.innerHTML = `
                    <div class="wheel-center"><i class="fas fa-star"></i></div>
                `;
                return;
            }
            
            // Create SVG for pie slices
            const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.setAttribute('width', '100%');
            svg.setAttribute('height', '100%');
            svg.setAttribute('viewBox', '0 0 500 500');
            svg.style.position = 'absolute';
            svg.style.top = '0';
            svg.style.left = '0';
            svg.style.zIndex = '5';
            
            const centerX = 250;
            const centerY = 250;
            const radius = 240;
            const anglePerSection = 360 / totalSections;
            
            // Create sections based on number of players
            for (let i = 0; i < totalSections; i++) {
                const startAngle = i * anglePerSection;
                const endAngle = (i + 1) * anglePerSection;
                
                // Create SVG path for this slice
                const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                path.setAttribute('d', createPieSlicePath(centerX, centerY, radius, startAngle, endAngle));
                
                // Assign alternating colors for slices
                if (i % 2 === 0) {
                    path.setAttribute('fill', '#e74c3c'); // Red
                } else {
                    path.setAttribute('fill', '#3498db'); // Blue
                }
                path.setAttribute('stroke', 'rgba(255,255,255,0.3)');
                path.setAttribute('stroke-width', '2');
                
                svg.appendChild(path);
                
                // Create text element for this slice
                const textElement = document.createElement('div');
                textElement.className = 'section-text';
                textElement.textContent = players[i] || `Player ${i + 1}`;
                
                // Position text in the middle of the slice
                const textAngle = startAngle + (anglePerSection / 2);
                
                // Use consistent radius for all text
                const textRadius = radius * 0.6;
                
                const textX = centerX + textRadius * Math.cos((textAngle - 90) * Math.PI / 180);
                const textY = centerY + textRadius * Math.sin((textAngle - 90) * Math.PI / 180);
                
                // Calculate rotation to align text with slice angle
                // Add 90 degrees to account for text baseline, then rotate to match slice
                const textRotation = textAngle + 90;
                
                textElement.style.position = 'absolute';
                textElement.style.left = textX + 'px';
                textElement.style.top = textY + 'px';
                textElement.style.transform = `translate(-50%, -50%) rotate(${textRotation}deg)`;
                textElement.style.zIndex = '10';
                textElement.style.transformOrigin = 'center';
                
                // Use consistent font size for all text
                textElement.style.fontSize = '1rem';
                
                // Add text overflow handling
                textElement.style.overflow = 'hidden';
                textElement.style.textOverflow = 'ellipsis';
                textElement.style.whiteSpace = 'nowrap';
                
                wheel.appendChild(textElement);
                wheelSections.push({
                    element: textElement,
                    player: players[i],
                    number: i
                });
            }
            
            wheel.appendChild(svg);
        }
        
        function spinWheel() {
            if (isSpinning || players.length === 0) return;
            
            isSpinning = true;
            const spinButton = document.getElementById('spinButton');
            const wheel = document.getElementById('rouletteWheel');
            const winnerAnnouncement = document.getElementById('winnerAnnouncement');
            
            spinButton.disabled = true;
            spinButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Spinning...';
            
            // Hide previous winner announcement
            winnerAnnouncement.classList.remove('show');
            
            // Add spinning class
            wheel.classList.add('spinning');
            
            // Calculate random extra spins (3-8 full rotations)
            const extraSpins = Math.random() * 5 + 3;
            const extraRotation = extraSpins * 360;
            
            // Calculate final rotation (just extra spins, winner determined by final position)
            const finalRotation = extraRotation;
            
            // Set CSS variable for final rotation
            wheel.style.setProperty('--spin-rotation', `${finalRotation}deg`);
            
            // Show winner after animation completes
            setTimeout(() => {
                // Calculate winner based on final wheel position
                const actualWinner = calculateWinnerFromPosition(finalRotation);
                showWinner(actualWinner.winner, actualWinner.winnerNumber);
                createConfetti();
            }, 4000);
        }
        
        // Calculate winner based on final wheel position
        function calculateWinnerFromPosition(finalRotation) {
            const totalSections = players.length;
            const anglePerSection = 360 / totalSections;
            
            // Normalize rotation to 0-360 range
            const normalizedRotation = ((finalRotation % 360) + 360) % 360;
            
            // Calculate which section the arrow points to
            // Arrow points to top (0 degrees), so we need to account for this
            const sectionAngle = (360 - normalizedRotation) % 360;
            const winnerNumber = Math.floor(sectionAngle / anglePerSection);
            
            // Ensure winner number is within valid range
            const validWinnerNumber = winnerNumber % totalSections;
            const winner = players[validWinnerNumber];
            
            return {
                winner: winner,
                winnerNumber: validWinnerNumber
            };
        }
        
        function showWinner(winner, winningNumber) {
            const winnerName = document.getElementById('winnerName');
            const winnerNumber = document.getElementById('winnerNumber');
            const winnerPopupOverlay = document.getElementById('winnerPopupOverlay');
            const winnerAnnouncement = document.getElementById('winnerAnnouncement');
            
            winnerName.textContent = winner;
            winnerNumber.textContent = `Winning Number: ${winningNumber}`;
            
            // Show popup overlay first
            winnerPopupOverlay.classList.add('show');
            
            // Add animation to the announcement after a short delay
            setTimeout(() => {
                winnerAnnouncement.classList.add('show');
            }, 100);
            
            // Reset button
            resetButton();
        }
        
        function closeWinnerPopup() {
            const winnerPopupOverlay = document.getElementById('winnerPopupOverlay');
            const winnerAnnouncement = document.getElementById('winnerAnnouncement');
            
            // Remove show class from announcement first
            winnerAnnouncement.classList.remove('show');
            
            // Hide overlay after animation completes
            setTimeout(() => {
                winnerPopupOverlay.classList.remove('show');
            }, 300);
        }
        
        function resetButton() {
            const spinButton = document.getElementById('spinButton');
            const wheel = document.getElementById('rouletteWheel');
            
            isSpinning = false;
            spinButton.disabled = false;
            spinButton.innerHTML = '<i class="fas fa-play"></i> Spin Again';
            wheel.classList.remove('spinning');
        }
        
        function createConfetti() {
            const colors = ['#ffd700', '#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#feca57'];
            
            for (let i = 0; i < 50; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti';
                    confetti.style.left = Math.random() * 100 + '%';
                    confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.animationDelay = Math.random() * 3 + 's';
                    confetti.style.animationDuration = (Math.random() * 3 + 2) + 's';
                    
                    document.body.appendChild(confetti);
                    
                    setTimeout(() => {
                        confetti.remove();
                    }, 5000);
                }, i * 50);
            }
        }
        
        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Set up event listeners
            const playersTextarea = document.getElementById('playersTextarea');
            
            // Auto-update players as user types
            playersTextarea.addEventListener('input', autoUpdatePlayers);
            
            // Auto-update players on paste
            playersTextarea.addEventListener('paste', function() {
                // Wait for paste to complete, then update
                setTimeout(autoUpdatePlayers, 10);
            });
            
            // Initialize player list as visible by default
            const playerSection = document.getElementById('playerSection');
            const showToggleBtn = document.getElementById('showToggleBtn');
            
            // Ensure player list starts visible
            playerListVisible = true;
            playerSection.classList.remove('collapsed');
            showToggleBtn.classList.remove('visible');
            
            // Auto-focus the textarea
            playersTextarea.focus();
            
            // Initialize with empty player list and show empty wheel
            players = [];
            updateRouletteWheel();
            hideGameControls();
        });
    </script>
</body>
</html>