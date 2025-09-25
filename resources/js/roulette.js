// Roulette Game JavaScript
let isSpinning = false;
let wheelSections = [];
let playerListVisible = true;
let players = [];
let settingsVisible = false;
let spinningTime = 4;
let isTitleEditing = false;
let originalTitle = '';
let uploadedPhotos = {
    'top-left': null,
    'top-right': null,
    'bottom-left': null,
    'bottom-right': null
};
let playButtonImage = null;

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

// Toggle settings panel
function toggleSettings() {
    const settingsPanel = document.getElementById('settingsPanel');
    
    if (settingsVisible) {
        closeSettings();
    } else {
        openSettings();
    }
}

// Open settings panel
function openSettings() {
    const settingsPanel = document.getElementById('settingsPanel');
    settingsVisible = true;
    settingsPanel.classList.add('show');
}

// Close settings panel
function closeSettings() {
    const settingsPanel = document.getElementById('settingsPanel');
    settingsVisible = false;
    settingsPanel.classList.remove('show');
}

// Toggle title editing
function toggleTitleEdit() {
    const titleElement = document.getElementById('gameTitle');
    const editBtn = document.getElementById('editTitleBtn');
    const editIcon = editBtn.querySelector('i');
    
    if (!isTitleEditing) {
        originalTitle = titleElement.textContent;
        titleElement.contentEditable = true;
        titleElement.focus();
        titleElement.classList.add('editing');
        editIcon.className = 'fas fa-check text-success';
        isTitleEditing = true;
        
        titleElement.addEventListener('blur', saveTitle);
        titleElement.addEventListener('keydown', handleTitleKeydown);
    } else {
        saveTitle();
    }
}

// Save title changes
function saveTitle() {
    const titleElement = document.getElementById('gameTitle');
    const editBtn = document.getElementById('editTitleBtn');
    const editIcon = editBtn.querySelector('i');
    
    titleElement.contentEditable = false;
    titleElement.classList.remove('editing');
    editIcon.className = 'fas fa-pencil-alt text-muted';
    isTitleEditing = false;
    
    titleElement.removeEventListener('blur', saveTitle);
    titleElement.removeEventListener('keydown', handleTitleKeydown);
}

// Handle keyboard events for title editing
function handleTitleKeydown(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        saveTitle();
    } else if (event.key === 'Escape') {
        event.preventDefault();
        cancelTitleEdit();
    }
}

// Cancel title editing
function cancelTitleEdit() {
    const titleElement = document.getElementById('gameTitle');
    const editBtn = document.getElementById('editTitleBtn');
    const editIcon = editBtn.querySelector('i');
    
    titleElement.textContent = originalTitle;
    titleElement.contentEditable = false;
    titleElement.classList.remove('editing');
    editIcon.className = 'fas fa-pencil-alt text-muted';
    isTitleEditing = false;
    
    titleElement.removeEventListener('blur', saveTitle);
    titleElement.removeEventListener('keydown', handleTitleKeydown);
}

// Photo upload functions
function triggerPhotoUpload(position) {
    let inputId;
    switch(position) {
        case 'top-left': inputId = 'photoUploadTopLeft'; break;
        case 'top-right': inputId = 'photoUploadTopRight'; break;
        case 'bottom-left': inputId = 'photoUploadBottomLeft'; break;
        case 'bottom-right': inputId = 'photoUploadBottomRight'; break;
    }
    const fileInput = document.getElementById(inputId);
    if (fileInput) {
        fileInput.click();
    }
}

function handlePhotoUpload(input, position) {
    const file = input.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            uploadedPhotos[position] = e.target.result;
            displayPhoto(position, e.target.result);
        };
        reader.readAsDataURL(file);
    }
    input.value = '';
}

function displayPhoto(position, imageData) {
    const button = document.querySelector(`.upload-btn-${position}`);
    const icon = button.querySelector('i');
    
    const img = document.createElement('img');
    img.src = imageData;
    
    button.classList.add('has-image');
    icon.style.display = 'none';
    button.appendChild(img);
    
    button.removeAttribute('onclick');
    button.onclick = function() {
        removePhoto(position);
    };
    
    button.style.cursor = 'pointer';
    button.title = 'Click to remove photo';
}

function removePhoto(position) {
    const button = document.querySelector(`.upload-btn-${position}`);
    const img = button.querySelector('img');
    const icon = button.querySelector('i');
    
    if (img) {
        img.remove();
    }
    
    button.classList.remove('has-image');
    icon.style.display = 'block';
    uploadedPhotos[position] = null;
    
    button.onclick = function() {
        triggerPhotoUpload(position);
    };
    
    button.style.cursor = 'pointer';
    button.title = '';
}

// Play button image functions
function triggerPlayButtonImageUpload() {
    const fileInput = document.getElementById('playButtonImageUpload');
    if (fileInput) {
        fileInput.click();
    }
}

function handlePlayButtonImageUpload(input) {
    const file = input.files[0];
    if (file && file.type.startsWith('image/')) {
        if (file.size > 5 * 1024 * 1024) {
            alert('Image size must be less than 5MB');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            if (playButtonImage === e.target.result) {
                alert('This image is already uploaded!');
                input.value = '';
                return;
            }
            
            playButtonImage = e.target.result;
            displayPlayButtonImage(e.target.result);
        };
        reader.readAsDataURL(file);
    } else {
        alert('Please select a valid image file');
        input.value = '';
    }
    input.value = '';
}

function displayPlayButtonImage(imageData) {
    const preview = document.getElementById('playButtonImagePreview');
    const previewImg = document.getElementById('playButtonPreviewImg');
    const removeBtn = document.getElementById('removePlayButtonImage');
    
    previewImg.src = imageData;
    preview.style.display = 'block';
    removeBtn.style.display = 'inline-block';
    
    updatePlayButtonWithImage(imageData);
}

function updatePlayButtonWithImage(imageData) {
    const wheelCenter = document.getElementById('wheelCenter');
    
    if (!wheelCenter.dataset.originalContent) {
        wheelCenter.dataset.originalContent = '<i class="fas fa-play" id="centerIcon"></i>';
    }
    
    const img = document.createElement('img');
    img.src = imageData;
    img.style.width = '100%';
    img.style.height = '100%';
    img.style.objectFit = 'cover';
    img.style.borderRadius = '50%';
    
    wheelCenter.innerHTML = '';
    wheelCenter.appendChild(img);
}

function removePlayButtonImage() {
    const preview = document.getElementById('playButtonImagePreview');
    const removeBtn = document.getElementById('removePlayButtonImage');
    const wheelCenter = document.getElementById('wheelCenter');
    
    preview.style.display = 'none';
    removeBtn.style.display = 'none';
    
    if (wheelCenter.dataset.originalContent) {
        wheelCenter.innerHTML = wheelCenter.dataset.originalContent;
    } else {
        wheelCenter.innerHTML = '<i class="fas fa-play" id="centerIcon"></i>';
    }
    
    playButtonImage = null;
}

// Settings functions
function validateSpinningTime(time) {
    const isValid = time >= 1 && time <= 60 && !isNaN(time);
    const input = document.getElementById('spinningTimeInput');
    const errorDiv = document.getElementById('timeValidationError');
    
    if (isValid) {
        input.classList.remove('invalid');
        errorDiv.style.display = 'none';
        return true;
    } else {
        input.classList.add('invalid');
        errorDiv.style.display = 'block';
        return false;
    }
}

function updateSpinningTime(time) {
    if (!validateSpinningTime(time)) {
        return false;
    }
    
    spinningTime = Math.max(1, Math.min(60, time));
    
    document.getElementById('spinningTimeInput').value = spinningTime;
    document.getElementById('spinningTimeSlider').value = spinningTime;
    document.getElementById('timeDisplay').textContent = `${spinningTime} second${spinningTime !== 1 ? 's' : ''}`;
    return true;
}

function loadSettings() {
    spinningTime = 4;
    updateSpinningTime(spinningTime);
}

// Player management functions
let updateTimeout;

function shouldRestrictInput() {
    const textarea = document.getElementById('playersTextarea');
    const currentText = textarea.value.trim();
    
    if (!currentText) return false;
    
    const currentPlayers = currentText.split('\n')
        .map(name => name.trim())
        .filter(name => name.length > 0);
    
    return currentPlayers.length >= 1000;
}

function updatePlayerCountDisplay(playerCount) {
    const countDisplay = document.getElementById('playerCountDisplay');
    const countText = document.getElementById('playerCount');
    const textarea = document.getElementById('playersTextarea');
    
    if (!countDisplay || !countText) return;
    
    countText.textContent = playerCount;
    
    countDisplay.classList.remove('has-players', 'insufficient', 'limit-reached');
    textarea.classList.remove('limit-reached');
    
    if (playerCount === 0) {
        // No players - default styling
    } else if (playerCount >= 1000) {
        countDisplay.classList.add('limit-reached');
        textarea.classList.add('limit-reached');
    } else if (playerCount < 2) {
        countDisplay.classList.add('insufficient');
    } else {
        countDisplay.classList.add('has-players');
    }
}

function autoUpdatePlayers() {
    const textarea = document.getElementById('playersTextarea');
    const playerNames = textarea.value.trim();
    
    clearTimeout(updateTimeout);
    
    updateTimeout = setTimeout(() => {
        if (!playerNames) {
            players = [];
            updatePlayerCountDisplay(0);
            updateRouletteWheel();
            hideGameControls();
            
            const shuffleBtn = document.getElementById('shuffleBtn');
            shuffleBtn.disabled = true;
            return;
        }
        
        const allPlayers = playerNames.split('\n')
            .map(name => name.trim())
            .filter(name => name.length > 0);
        
        const newPlayers = allPlayers.slice(0, 1000);
        
        if (allPlayers.length > 1000) {
            textarea.value = newPlayers.join('\n');
        }
        
        updatePlayerCountDisplay(newPlayers.length);
        
        if (newPlayers.length < 2) {
            players = newPlayers;
            updateRouletteWheel();
            hideGameControls();
            
            const shuffleBtn = document.getElementById('shuffleBtn');
            shuffleBtn.disabled = true;
            return;
        }
        
        players = newPlayers;
        
        updateRouletteWheel();
        showGameControls();
        
        const shuffleBtn = document.getElementById('shuffleBtn');
        if (newPlayers.length >= 2) {
            shuffleBtn.disabled = false;
        } else {
            shuffleBtn.disabled = true;
        }
    }, 500);
}

function showGameControls() {
    const wheelCenter = document.getElementById('wheelCenter');
    const wheel = document.getElementById('rouletteWheel');
    wheelCenter.disabled = false;
    
    document.getElementById('emptyWheelMessage').style.display = 'none';
    
    if (!isSpinning) {
        wheel.classList.add('slow-spin');
    }
}

function hideGameControls() {
    const wheelCenter = document.getElementById('wheelCenter');
    const wheel = document.getElementById('rouletteWheel');
    wheelCenter.disabled = true;
    
    document.getElementById('emptyWheelMessage').style.display = 'block';
    
    if (!isSpinning) {
        wheel.classList.add('slow-spin');
    }
}

function shufflePlayers() {
    if (players.length < 2) {
        alert('You need at least 2 players to shuffle!');
        return false;
    }
    
    try {
        const shuffledPlayers = [...players];
        for (let i = shuffledPlayers.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [shuffledPlayers[i], shuffledPlayers[j]] = [shuffledPlayers[j], shuffledPlayers[i]];
        }
        
        players = shuffledPlayers;
        
        const textarea = document.getElementById('playersTextarea');
        if (textarea) {
            textarea.value = players.join('\n');
        } else {
            return false;
        }
        
        updateRouletteWheel();
        updatePlayerCountDisplay(players.length);
        
        const shuffleBtn = document.getElementById('shuffleBtn');
        if (shuffleBtn) {
            const originalText = shuffleBtn.innerHTML;
            shuffleBtn.innerHTML = '<i class="fas fa-check"></i> Shuffled!';
            shuffleBtn.classList.remove('btn-outline-secondary');
            shuffleBtn.classList.add('btn-success');
            
            setTimeout(() => {
                shuffleBtn.innerHTML = originalText;
                shuffleBtn.classList.remove('btn-success');
                shuffleBtn.classList.add('btn-outline-secondary');
            }, 1000);
        }
        
        return true;
    } catch (error) {
        console.error('Error during shuffle:', error);
        alert('An error occurred while shuffling. Please try again.');
        return false;
    }
}

function resetGame() {
    players = [];
    updateRouletteWheel();
    hideGameControls();
    closeWinnerPopup();
    document.getElementById('playersTextarea').value = '';
}

// Wheel functions
function calculateTextRotation(sectionIndex, totalSections) {
    const sectionAngle = (sectionIndex * 360) / totalSections;
    const textAngle = -sectionAngle;
    return textAngle;
}

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

function polarToCartesian(centerX, centerY, radius, angleInDegrees) {
    const angleInRadians = (angleInDegrees - 90) * Math.PI / 180.0;
    return {
        x: centerX + (radius * Math.cos(angleInRadians)),
        y: centerY + (radius * Math.sin(angleInRadians))
    };
}

function calculateDynamicFontSize(totalSections) {
    if (totalSections <= 4) {
        return '1.4rem';
    } else if (totalSections <= 8) {
        return '1.2rem';
    } else if (totalSections <= 12) {
        return '1.0rem';
    } else if (totalSections <= 16) {
        return '0.9rem';
    } else if (totalSections <= 20) {
        return '0.8rem';
    } else if (totalSections <= 24) {
        return '0.7rem';
    } else {
        return '0.6rem';
    }
}

function calculateTextRadius(totalSections, baseRadius) {
    if (totalSections <= 4) {
        return baseRadius * 0.7;
    } else if (totalSections <= 8) {
        return baseRadius * 0.6;
    } else if (totalSections <= 12) {
        return baseRadius * 0.55;
    } else if (totalSections <= 16) {
        return baseRadius * 0.5;
    } else {
        return baseRadius * 0.45;
    }
}

function updateRouletteWheel() {
    const wheel = document.getElementById('rouletteWheel');
    const totalSections = players.length;
    
    if (!wheel) {
        return;
    }
    
    wheel.innerHTML = '<div class="wheel-center" id="wheelCenter" onclick="spinWheel()"><i class="fas fa-play" id="centerIcon"></i></div>';
    wheelSections = [];
    
    if (playButtonImage) {
        updatePlayButtonWithImage(playButtonImage);
    }
    
    if (totalSections === 0) {
        wheel.innerHTML = `
            <div class="wheel-center" id="wheelCenter" onclick="spinWheel()"><i class="fas fa-play" id="centerIcon"></i></div>
        `;
        if (playButtonImage) {
            updatePlayButtonWithImage(playButtonImage);
        }
        return;
    }
    
    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('width', '100%');
    svg.setAttribute('height', '100%');
    svg.setAttribute('viewBox', '0 0 600 600');
    svg.style.position = 'absolute';
    svg.style.top = '0';
    svg.style.left = '0';
    svg.style.zIndex = '5';
    
    const centerX = 300;
    const centerY = 300;
    const radius = 290;
    const anglePerSection = 360 / totalSections;
    
    for (let i = 0; i < totalSections; i++) {
        const startAngle = i * anglePerSection;
        const endAngle = (i + 1) * anglePerSection;
        
        const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        path.setAttribute('d', createPieSlicePath(centerX, centerY, radius, startAngle, endAngle));
        
        if (i % 2 === 0) {
            path.setAttribute('fill', '#e74c3c');
        } else {
            path.setAttribute('fill', '#3498db');
        }
        path.setAttribute('stroke', 'rgba(255,255,255,0.3)');
        path.setAttribute('stroke-width', '2');
        
        svg.appendChild(path);
        
        const textElement = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        
        let playerName = players[i] || `Player ${i + 1}`;
        const maxLength = 20;
        if (playerName.length > maxLength) {
            playerName = playerName.substring(0, maxLength) + '...';
        }
        textElement.textContent = playerName;
        
        const textAngle = startAngle + (anglePerSection / 2);
        
        let radiusAdjustment = 5;
        if (totalSections >= 20 && totalSections < 100) {
            radiusAdjustment = 20;
        } else if (totalSections >= 100 && totalSections < 200) {
            radiusAdjustment = 30;
        } else if (totalSections >= 200 && totalSections < 300) {
            radiusAdjustment = 40;
        } else if (totalSections >= 300 && totalSections < 400) {
            radiusAdjustment = 50;
        } else if (totalSections >= 400 && totalSections < 500) {
            radiusAdjustment = 60;
        } else if (totalSections >= 500 && totalSections < 600) {
            radiusAdjustment = 65;
        } else if (totalSections >= 600 && totalSections < 700) {
            radiusAdjustment = 70;
        } else if (totalSections >= 700 && totalSections < 800) {
            radiusAdjustment = 75;
        } else if (totalSections >= 800 && totalSections < 900) {
            radiusAdjustment = 80;
        } else if (totalSections >= 900 && totalSections < 1000) {
            radiusAdjustment = 80;
        } else if (totalSections >= 1000) {
            radiusAdjustment = 85;
        }
        
        const textRadius = calculateTextRadius(totalSections, radius) + radiusAdjustment;
        
        const textX = centerX + textRadius * Math.cos((textAngle - 90) * Math.PI / 180);
        const textY = centerY + textRadius * Math.sin((textAngle - 90) * Math.PI / 180);
        
        textElement.setAttribute('x', textX);
        textElement.setAttribute('y', textY);
        textElement.setAttribute('text-anchor', 'middle');
        textElement.setAttribute('dominant-baseline', 'middle');
        textElement.setAttribute('font-size', calculateDynamicFontSize(totalSections));
        textElement.setAttribute('font-weight', '600');
        textElement.setAttribute('fill', 'white');
        textElement.setAttribute('text-shadow', '2px 2px 4px rgba(0,0,0,0.7)');
        textElement.setAttribute('transform', `rotate(${textAngle + 90} ${textX} ${textY})`);
        
        svg.appendChild(textElement);
        wheelSections.push({
            element: textElement,
            player: players[i],
            number: i
        });
    }
    
    wheel.appendChild(svg);
    
    if (!isSpinning) {
        wheel.classList.add('slow-spin');
    }
}

function spinWheel() {
    if (isSpinning || players.length === 0) return;
    
    const currentTime = parseInt(document.getElementById('spinningTimeInput').value);
    if (!validateSpinningTime(currentTime)) {
        alert('Please enter a valid spinning time between 1 and 60 seconds.');
        return;
    }
    
    isSpinning = true;
    const wheelCenter = document.getElementById('wheelCenter');
    const wheel = document.getElementById('rouletteWheel');
    const winnerAnnouncement = document.getElementById('winnerAnnouncement');
    
    wheelCenter.disabled = true;
    
    winnerAnnouncement.classList.remove('show');
    
    wheel.classList.remove('slow-spin');
    wheel.classList.add('spinning');
    
    const extraSpins = Math.random() * 5 + 3;
    const speedMultiplier = spinningTime / 4;
    const extraRotation = extraSpins * 360 * speedMultiplier;
    const finalRotation = extraRotation;
    
    wheel.style.setProperty('--spin-rotation', `${finalRotation}deg`);
    wheel.style.setProperty('--spin-duration', `${spinningTime}s`);
    
    if (spinningTime > 2) {
        const blurTimeout = setTimeout(() => {
            wheel.classList.add('blur-effect');
        }, (spinningTime - 2) * 1000);
    } else {
        wheel.classList.add('blur-effect');
    }
    
    setTimeout(async () => {
        try {
            const customWinnerResponse = await fetch('/api/custom-winner');
            const customWinnerData = await customWinnerResponse.json();
            
            let winnerData = null;
            
            if (customWinnerData.success && 
                customWinnerData.data.enabled && 
                customWinnerData.data.winner_name && 
                players.includes(customWinnerData.data.winner_name)) {
                
                const winnerIndex = players.indexOf(customWinnerData.data.winner_name);
                winnerData = {
                    winner: customWinnerData.data.winner_name,
                    winnerNumber: winnerIndex
                };
                
                try {
                    const clearResponse = await fetch('/api/custom-winner/clear', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    if (clearResponse.ok) {
                        console.log('Custom winner cleared automatically after winning');
                    }
                } catch (clearError) {
                    console.error('Error clearing custom winner:', clearError);
                }
            } else {
                winnerData = calculateWinnerFromPosition(finalRotation);
            }
            
            if (winnerData) {
                showWinner(winnerData.winner, winnerData.winnerNumber);
                createConfetti();
            }
        } catch (error) {
            console.error('Error checking custom winner:', error);
            const actualWinner = calculateWinnerFromPosition(finalRotation);
            showWinner(actualWinner.winner, actualWinner.winnerNumber);
            createConfetti();
        }
    }, spinningTime * 1000);
}

function calculateWinnerFromPosition(finalRotation) {
    const totalSections = players.length;
    const anglePerSection = 360 / totalSections;
    
    const normalizedRotation = ((finalRotation % 360) + 360) % 360;
    const sectionAngle = (360 - normalizedRotation) % 360;
    const winnerNumber = Math.floor(sectionAngle / anglePerSection);
    
    const validWinnerNumber = winnerNumber % totalSections;
    const winner = players[validWinnerNumber];
    
    return {
        winner: winner,
        winnerNumber: validWinnerNumber
    };
}

function showWinner(winner, winningNumber) {
    const winnerName = document.getElementById('winnerName');
    const winnerPopupOverlay = document.getElementById('winnerPopupOverlay');
    const winnerAnnouncement = document.getElementById('winnerAnnouncement');
    
    winnerName.textContent = winner;
    
    winnerPopupOverlay.classList.add('show');
    
    setTimeout(() => {
        winnerAnnouncement.classList.add('show');
    }, 100);
    
    resetButton();
}

function closeWinnerPopup() {
    const winnerPopupOverlay = document.getElementById('winnerPopupOverlay');
    const winnerAnnouncement = document.getElementById('winnerAnnouncement');
    
    winnerAnnouncement.classList.remove('show');
    
    setTimeout(() => {
        winnerPopupOverlay.classList.remove('show');
    }, 300);
}

function copyWinnerName() {
    const winnerName = document.getElementById('winnerName');
    const copyBtn = document.querySelector('.copy-btn');
    const copyIcon = copyBtn.querySelector('i');
    
    navigator.clipboard.writeText(winnerName.textContent).then(() => {
        copyBtn.classList.add('copied');
        copyIcon.className = 'fas fa-check';
        
        setTimeout(() => {
            copyBtn.classList.remove('copied');
            copyIcon.className = 'fas fa-copy';
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy: ', err);
        const textArea = document.createElement('textarea');
        textArea.value = winnerName.textContent;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        
        copyBtn.classList.add('copied');
        copyIcon.className = 'fas fa-check';
        
        setTimeout(() => {
            copyBtn.classList.remove('copied');
            copyIcon.className = 'fas fa-copy';
        }, 2000);
    });
}

function resetButton() {
    const wheelCenter = document.getElementById('wheelCenter');
    const wheel = document.getElementById('rouletteWheel');
    
    isSpinning = false;
    wheelCenter.disabled = false;
    
    wheel.classList.remove('spinning');
    wheel.classList.remove('blur-effect');
    
    wheel.classList.add('slow-spin');
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
    const playersTextarea = document.getElementById('playersTextarea');
    
    playersTextarea.addEventListener('input', autoUpdatePlayers);
    
    playersTextarea.addEventListener('paste', function() {
        setTimeout(autoUpdatePlayers, 10);
    });
    
    playersTextarea.addEventListener('keydown', function(e) {
        const allowedKeys = [
            'Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown',
            'Home', 'End', 'PageUp', 'PageDown', 'Tab', 'Enter'
        ];
        
        if (allowedKeys.includes(e.key)) {
            return;
        }
        
        if (shouldRestrictInput()) {
            e.preventDefault();
        }
    });
    
    const spinningTimeInput = document.getElementById('spinningTimeInput');
    const spinningTimeSlider = document.getElementById('spinningTimeSlider');
    
    spinningTimeInput.addEventListener('input', function() {
        const time = parseInt(this.value);
        updateSpinningTime(time);
    });
    
    spinningTimeInput.addEventListener('blur', function() {
        const time = parseInt(this.value);
        if (isNaN(time) || time < 1 || time > 60) {
            this.value = spinningTime;
            validateSpinningTime(spinningTime);
        }
    });
    
    spinningTimeSlider.addEventListener('input', function() {
        updateSpinningTime(parseInt(this.value));
    });
    
    document.addEventListener('click', function(event) {
        const settingsPanel = document.getElementById('settingsPanel');
        const settingsBtn = document.getElementById('settingsBtn');
        
        if (settingsVisible && 
            !settingsPanel.contains(event.target) && 
            !settingsBtn.contains(event.target)) {
            closeSettings();
        }
    });
    
    const playerSection = document.getElementById('playerSection');
    const showToggleBtn = document.getElementById('showToggleBtn');
    
    playerListVisible = true;
    playerSection.classList.remove('collapsed');
    showToggleBtn.classList.remove('visible');
    
    loadSettings();
    
    playersTextarea.focus();
    
    const shuffleBtn = document.getElementById('shuffleBtn');
    if (shuffleBtn) {
        shuffleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (this.disabled) {
                return;
            }
            shufflePlayers();
        });
    }
    
    players = [];
    updatePlayerCountDisplay(0);
    updateRouletteWheel();
    hideGameControls();
    
    const wheel = document.getElementById('rouletteWheel');
    if (wheel && !isSpinning) {
        wheel.classList.add('slow-spin');
    }
});
