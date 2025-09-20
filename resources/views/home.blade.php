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
        }
        
        .main-container {
            display: flex;
            min-height: 100vh;
            padding: 2rem 0;
        }
        
        .game-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0 2rem;
            position: relative;
        }
        
        .player-section {
            width: 300px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            margin-right: 2rem;
            transition: transform 0.3s ease;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .player-section.collapsed {
            transform: translateX(250px);
        }
        
        .toggle-btn {
            position: absolute;
            top: 20px;
            right: -40px;
            background: #dc3545;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .toggle-btn:hover {
            background: #c82333;
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
            width: 400px;
            height: 400px;
            margin: 2rem auto;
            min-width: 300px;
            min-height: 300px;
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
        
        .winner-announcement {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            margin: 2rem 0;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.5s ease;
        }
        
        .winner-announcement.show {
            opacity: 1;
            transform: scale(1);
        }
        
        .winner-name {
            font-size: 2.5rem;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .winner-number {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 1rem;
        }
        
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
        
        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
            }
            
            .player-section {
                width: 100%;
                margin-right: 0;
                margin-bottom: 2rem;
            }
            
            .wheel-container {
                width: 300px;
                height: 300px;
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
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Player Input Section -->
        <div class="player-section" id="playerSection">
            <button class="toggle-btn" onclick="togglePlayerList()">
                <i class="fas fa-chevron-right" id="toggleIcon"></i>
            </button>
            
            <div class="player-section-header">
                <h3><i class="fas fa-users"></i> Players</h3>
            </div>
            
            <div class="player-input-area">
                <textarea 
                    class="form-control" 
                    id="playersTextarea" 
                    rows="10" 
                    placeholder="Enter player names, one per line...&#10;&#10;Example:&#10;John Doe&#10;Jane Smith&#10;Mike Johnson&#10;&#10;The roulette will update automatically as you type!"
                    style="resize: vertical; min-height: 200px;"
                ></textarea>
            </div>
        </div>
        
        <!-- Game Section -->
        <div class="game-section">
            <div class="game-content">
                <div class="game-title">
                    <h1><i class="fas fa-dice"></i> Roulette Game</h1>
                    <p>Enter player names in the textarea and spin the wheel to find your winner!</p>
                </div>
                
                
                <!-- Roulette Wheel (always visible now) -->
                <div class="wheel-container" id="wheelContainer" style="display: none;">
                    <div class="pointer"></div>
                    <div class="roulette-wheel" id="rouletteWheel">
                        <div class="wheel-center">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                
                <button class="btn spin-button" id="spinButton" onclick="spinWheel()" style="display: none;">
                    <i class="fas fa-play"></i> Spin the Wheel
                </button>
                
                <div class="winner-announcement" id="winnerAnnouncement">
                    <div class="winner-name" id="winnerName"></div>
                    <div class="winner-number" id="winnerNumber"></div>
                    <p class="mb-0">🎉 Congratulations! 🎉</p>
                </div>
                
                <div class="action-buttons" id="actionButtons" style="display: none;">
                    <button class="btn btn-secondary" onclick="resetGame()">
                        <i class="fas fa-refresh"></i> New Game
                    </button>
                    <a href="{{ route('winners') }}" class="btn btn-secondary">
                        <i class="fas fa-trophy"></i> Past Winners
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let isSpinning = false;
        let wheelSections = [];
        let playerListVisible = true;
        let players = [];
        
        // Toggle player list visibility
        function togglePlayerList() {
            const playerSection = document.getElementById('playerSection');
            const toggleIcon = document.getElementById('toggleIcon');
            
            playerListVisible = !playerListVisible;
            
            if (playerListVisible) {
                playerSection.classList.remove('collapsed');
                toggleIcon.className = 'fas fa-chevron-right';
            } else {
                playerSection.classList.add('collapsed');
                toggleIcon.className = 'fas fa-chevron-left';
            }
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
                
                // Remove duplicates while preserving order
                players = [...new Set(newPlayers)];
                
                // Update roulette wheel and show game controls
                updateRouletteWheel();
                showGameControls();
            }, 500); // 500ms delay to avoid too frequent updates
        }
        
        // Show game controls
        function showGameControls() {
            document.getElementById('wheelContainer').style.display = 'block';
            document.getElementById('spinButton').style.display = 'inline-block';
            document.getElementById('actionButtons').style.display = 'flex';
        }
        
        // Hide game controls
        function hideGameControls() {
            document.getElementById('wheelContainer').style.display = 'none';
            document.getElementById('spinButton').style.display = 'none';
            document.getElementById('actionButtons').style.display = 'none';
        }
        
        // Reset game
        function resetGame() {
            players = [];
            updateRouletteWheel();
            hideGameControls();
            document.getElementById('winnerAnnouncement').classList.remove('show');
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
            
            if (!wheel || totalSections === 0) {
                return;
            }
            
            // Clear any existing sections
            wheel.innerHTML = '<div class="wheel-center"><i class="fas fa-star"></i></div>';
            wheelSections = [];
            
            // Create SVG for pie slices
            const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.setAttribute('width', '100%');
            svg.setAttribute('height', '100%');
            svg.setAttribute('viewBox', '0 0 400 400');
            svg.style.position = 'absolute';
            svg.style.top = '0';
            svg.style.left = '0';
            svg.style.zIndex = '5';
            
            const centerX = 200;
            const centerY = 200;
            const radius = 190;
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
                const textRadius = radius * 0.6;
                const textX = centerX + textRadius * Math.cos((textAngle - 90) * Math.PI / 180);
                const textY = centerY + textRadius * Math.sin((textAngle - 90) * Math.PI / 180);
                
                textElement.style.position = 'absolute';
                textElement.style.left = textX + 'px';
                textElement.style.top = textY + 'px';
                textElement.style.transform = 'translate(-50%, -50%)';
                textElement.style.zIndex = '10';
                
                // Adjust font size based on name length
                const nameLength = (players[i] || '').length;
                if (nameLength > 15) {
                    textElement.style.fontSize = '0.7rem';
                } else if (nameLength > 10) {
                    textElement.style.fontSize = '0.8rem';
                } else {
                    textElement.style.fontSize = '1rem';
                }
                
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
            
            // Calculate random winner
            const winningNumber = Math.floor(Math.random() * players.length);
            const winner = players[winningNumber];
            
            // Calculate final rotation
            const totalSections = players.length;
            const baseRotation = winningNumber * (360 / totalSections);
            const extraSpins = Math.random() * 5 + 3; // 3-8 full rotations
            const finalRotation = baseRotation + (extraSpins * 360);
            
            // Set CSS variable for final rotation
            wheel.style.setProperty('--spin-rotation', `${finalRotation}deg`);
            
            // Show winner after animation completes
            setTimeout(() => {
                showWinner(winner, winningNumber);
                createConfetti();
            }, 4000);
        }
        
        function showWinner(winner, winningNumber) {
            const winnerName = document.getElementById('winnerName');
            const winnerNumber = document.getElementById('winnerNumber');
            const winnerAnnouncement = document.getElementById('winnerAnnouncement');
            
            winnerName.textContent = winner;
            winnerNumber.textContent = `Winning Number: ${winningNumber}`;
            
            winnerAnnouncement.classList.add('show');
            
            // Reset button
            resetButton();
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
            
            // Auto-focus the textarea
            playersTextarea.focus();
            
            // Initialize with empty player list (session players removed)
            // Players will now only be loaded when manually added by the user
        });
    </script>
</body>
</html>