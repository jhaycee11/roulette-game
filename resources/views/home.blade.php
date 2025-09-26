<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Roulette Game - Spin the Wheel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/roulette.css', 'resources/js/app.js', 'resources/js/roulette.js'])
</head>
<body>
    <div class="main-container">
        <!-- Settings Button -->
        <button class="settings-btn" id="settingsBtn" onclick="toggleSettings()">
            <i class="fas fa-cog"></i>
        </button>

         <!-- Upload buttons positioned around the wheel -->
         <button class="upload-btn upload-btn-top-left" onclick="triggerPhotoUpload('top-left')">
            <i class="fas fa-plus"></i>
            <input type="file" id="photoUploadTopLeft" accept="image/*" style="display: none;" onchange="handlePhotoUpload(this, 'top-left')">
        </button>
        <button class="upload-btn upload-btn-top-right" onclick="triggerPhotoUpload('top-right')">
            <i class="fas fa-plus"></i>
            <input type="file" id="photoUploadTopRight" accept="image/*" style="display: none;" onchange="handlePhotoUpload(this, 'top-right')">
        </button>
        <button class="upload-btn upload-btn-bottom-left" onclick="triggerPhotoUpload('bottom-left')">
            <i class="fas fa-plus"></i>
            <input type="file" id="photoUploadBottomLeft" accept="image/*" style="display: none;" onchange="handlePhotoUpload(this, 'bottom-left')">
        </button>
        <button class="upload-btn upload-btn-bottom-right" onclick="triggerPhotoUpload('bottom-right')">
            <i class="fas fa-plus"></i>
            <input type="file" id="photoUploadBottomRight" accept="image/*" style="display: none;" onchange="handlePhotoUpload(this, 'bottom-right')">
        </button>
                    
        
        <!-- Settings Panel -->
        <div class="settings-panel" id="settingsPanel">
            <div class="settings-header">
                <h4><i class="fas fa-cog"></i> Settings</h4>
                <button class="settings-close-btn" onclick="closeSettings()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="setting-item">
                <label class="setting-label">Wheel Spinning Time</label>
                <div class="time-input-group">
                    <input type="number" class="time-input" id="spinningTimeInput" min="1" max="60" step="1" value="4" required>
                    <span class="time-unit">seconds</span>
                </div>
                <div class="validation-error" id="timeValidationError" style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 0.25rem;">
                    Please enter a value between 1 and 60 seconds
                </div>
                <input type="range" class="time-slider" id="spinningTimeSlider" min="1" max="60" value="4">
                <div class="time-display" id="timeDisplay">4 seconds</div>
            </div>
            
            <div class="setting-item">
                <label class="setting-label">Custom Play Button Image</label>
                <div class="upload-section">
                    <input type="file" id="playButtonImageUpload" accept="image/*" style="display: none;" onchange="handlePlayButtonImageUpload(this)">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="triggerPlayButtonImageUpload()">
                        <i class="fas fa-upload"></i> Upload Image
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm ms-2" id="removePlayButtonImage" onclick="removePlayButtonImage()" style="display: none;">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
                <div class="image-preview" id="playButtonImagePreview" style="display: none; margin-top: 0.5rem;">
                    <img id="playButtonPreviewImg" src="" alt="Preview" style="max-width: 100px; max-height: 100px; border-radius: 8px; border: 2px solid #e9ecef;">
                </div>
                <small class="text-muted">Upload a custom image for the play button. Recommended size: 100x100px or larger.</small>
            </div>
        </div>
        
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
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <button class="btn btn-outline-secondary btn-sm" id="shuffleBtn">
                        <i class="fas fa-random"></i> Shuffle
                    </button>
                </div>
                <textarea 
                    class="form-control" 
                    id="playersTextarea" 
                    rows="20" 
                    placeholder="Enter player names"
                    style="resize: vertical; min-height: 300px; overflow-y: auto;"
                ></textarea>
                <div class="player-count-display" id="playerCountDisplay">
                    <i class="fas fa-users"></i> <span id="playerCount">0</span> / 1,000 players
                </div>
            </div>
        </div>
        
        <!-- Game Section -->
        <div class="game-section">
            <div class="game-content">
                <div class="game-title">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <h1><i class="fas fa-dice"></i> <span id="gameTitle" contenteditable="false">Roulette Game</span></h1>
                            <button type="button" class="btn btn-link p-0 ms-2" id="editTitleBtn" onclick="toggleTitleEdit()">
                                <i class="fas fa-pencil-alt text-muted"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Roulette Wheel (always visible now) -->
                <div class="wheel-container" id="wheelContainer">
                    <div class="pointer"></div>
                    <div class="roulette-wheel slow-spin" id="rouletteWheel">
                        <div class="wheel-center" id="wheelCenter" onclick="spinWheel()">
                            <i class="fas fa-play" id="centerIcon"></i>
                        </div>
                    </div>
                </div>
                
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
                        
                        <div class="winner-name-container">
                            <div class="winner-name" id="winnerName"></div>
                            <button class="copy-btn" onclick="copyWinnerName()" title="Copy winner name">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <div class="congratulations-text">🎉 Congratulations! 🎉</div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
