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
            margin: 0 auto;
        }
        
        .game-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0 2rem;
            position: relative;
            max-width: 1000px;
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
        
        .settings-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background: #6c757d;
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
        }
        
        .settings-btn:hover {
            background: #5a6268;
            transform: scale(1.1);
        }
        
        .debug-btn {
            position: fixed;
            top: 20px;
            left: 80px;
            background: #17a2b8;
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
        }
        
        .debug-btn:hover {
            background: #138496;
            transform: scale(1.1);
        }
        
        .settings-panel {
            position: fixed;
            top: 70px;
            left: 20px;
            width: 300px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-20px);
        }
        
        .settings-panel.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .settings-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e9ecef;
        }
        
        .settings-header h4 {
            color: #333;
            margin: 0;
            font-weight: bold;
            font-size: 1.1rem;
        }
        
        .settings-close-btn {
            background: #dc3545;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            color: white;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .settings-close-btn:hover {
            background: #c82333;
            transform: scale(1.1);
        }
        
        .setting-item {
            margin-bottom: 1rem;
        }
        
        .setting-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
            font-size: 0.9rem;
        }
        
        .time-input-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .time-input {
            flex: 1;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.5rem;
            font-size: 0.9rem;
            transition: border-color 0.3s ease;
        }
        
        .time-input:focus {
            border-color: #6c757d;
            box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.25);
            outline: none;
        }
        
        .time-input.invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        
        .time-input.invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        
        .time-unit {
            color: #6c757d;
            font-size: 0.8rem;
            font-weight: 500;
            min-width: 30px;
        }
        
        .time-slider {
            width: 100%;
            margin-top: 0.5rem;
            -webkit-appearance: none;
            appearance: none;
            height: 6px;
            border-radius: 3px;
            background: #e9ecef;
            outline: none;
        }
        
        .time-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #6c757d;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .time-slider::-webkit-slider-thumb:hover {
            background: #5a6268;
            transform: scale(1.1);
        }
        
        .time-slider::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #6c757d;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
        }
        
        .time-slider::-moz-range-thumb:hover {
            background: #5a6268;
            transform: scale(1.1);
        }
        
        .time-display {
            text-align: center;
            margin-top: 0.5rem;
            font-size: 0.8rem;
            color: #6c757d;
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
            width: 600px;
            height: 600px;
            margin: 2rem auto;
            min-width: 500px;
            min-height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
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
            animation: spin var(--spin-duration, 4s) cubic-bezier(0.23, 1, 0.320, 1);
        }
        
        .roulette-wheel.slow-spin {
            animation: slowSpin 8s linear infinite;
        }
        
        .roulette-wheel.blur-effect {
            filter: blur(3px);
            transition: filter 0.5s ease-in-out;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(var(--spin-rotation, 1800deg)); }
        }
        
        @keyframes slowSpin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
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
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
            z-index: 20;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .wheel-center:hover:not(:disabled) {
            transform: translate(-50%, -50%) scale(1.1);
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.6);
            background: linear-gradient(135deg, #c82333 0%, #d63384 100%);
        }
        
        .wheel-center:active {
            transform: translate(-50%, -50%) scale(0.95);
        }
        
        .wheel-center:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: translate(-50%, -50%) scale(1);
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
            max-width: 650px;
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
        
        .winner-name-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        
        .winner-name {
            font-size: 3rem;
            font-weight: bold;
            color: #fff;
            margin-bottom: 0;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.3);
            animation: bounceIn 0.8s ease-out 0.3s both;
            flex: 1;
            min-width: 0;
            word-break: break-word;
            line-height: 1.2;
        }
        
        .copy-btn {
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            width: 45px;
            height: 45px;
            color: white;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: slideInUp 0.6s ease-out 0.5s both;
            flex-shrink: 0;
            min-width: 45px;
            min-height: 45px;
        }
        
        .copy-btn:hover {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.5);
            transform: scale(1.1);
        }
        
        .copy-btn:active {
            transform: scale(0.95);
        }
        
        .copy-btn.copied {
            background: rgba(40, 167, 69, 0.8);
            border-color: #28a745;
        }
        
        .copy-btn.copied i {
            animation: checkmark 0.3s ease;
        }
        
        @keyframes checkmark {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
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
            max-width: 800px;
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
            
            .settings-btn {
                top: 15px;
                left: 15px;
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }
            
            .debug-btn {
                top: 15px;
                left: 65px;
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }
            
            .settings-panel {
                top: 60px;
                left: 15px;
                right: 15px;
                width: auto;
                max-width: calc(100vw - 30px);
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
                width: 450px;
                height: 450px;
            }
            
            .winner-name {
                font-size: 2rem;
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
                line-height: 1.1;
            }
            
            .copy-btn {
                width: 40px;
                height: 40px;
                font-size: 1rem;
                min-width: 40px;
                min-height: 40px;
            }
            
            .winner-name-container {
                flex-direction: column;
                gap: 0.8rem;
            }
            
            .congratulations-text {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Settings Button -->
        <button class="settings-btn" id="settingsBtn" onclick="toggleSettings()">
            <i class="fas fa-cog"></i>
        </button>
        
        <!-- Debug Button -->
        <button class="debug-btn" id="debugBtn" onclick="showDebugInfo()">
            <i class="fas fa-bug"></i>
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
                    <div class="roulette-wheel slow-spin" id="rouletteWheel">
                        <div class="wheel-center" id="wheelCenter" onclick="spinWheel()">
                            <i class="fas fa-play" id="centerIcon"></i>
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
    <script>
        let isSpinning = false;
        let wheelSections = [];
        let playerListVisible = true; // Default to visible
        let players = [];
        let settingsVisible = false;
        let spinningTime = 4; // Default spinning time in seconds
        
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
        
        // Validate spinning time input
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
        
        // Update spinning time
        function updateSpinningTime(time) {
            // Validate the input first
            if (!validateSpinningTime(time)) {
                return false;
            }
            
            spinningTime = Math.max(1, Math.min(60, time)); // Clamp between 1-60 seconds
            // No localStorage - settings are not persisted
            
            // Update input and slider
            document.getElementById('spinningTimeInput').value = spinningTime;
            document.getElementById('spinningTimeSlider').value = spinningTime;
            document.getElementById('timeDisplay').textContent = `${spinningTime} second${spinningTime !== 1 ? 's' : ''}`;
            return true;
        }
        
        // Load settings (no persistence)
        function loadSettings() {
            // Settings are not persisted - use default values
            spinningTime = 4; // Default spinning time
            updateSpinningTime(spinningTime);
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
            const wheelCenter = document.getElementById('wheelCenter');
            const centerIcon = document.getElementById('centerIcon');
            const wheel = document.getElementById('rouletteWheel');
            wheelCenter.disabled = false;
            centerIcon.className = 'fas fa-play';
            document.getElementById('emptyWheelMessage').style.display = 'none';
            
            // Always start slow spin when not spinning
            if (!isSpinning) {
                wheel.classList.add('slow-spin');
            }
        }
        
        // Hide game controls
        function hideGameControls() {
            const wheelCenter = document.getElementById('wheelCenter');
            const centerIcon = document.getElementById('centerIcon');
            const wheel = document.getElementById('rouletteWheel');
            wheelCenter.disabled = true;
            centerIcon.className = 'fas fa-play';
            document.getElementById('emptyWheelMessage').style.display = 'block';
            
            // Keep slow spin running even when no players (continuous animation)
            if (!isSpinning) {
                wheel.classList.add('slow-spin');
            }
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
        
        // Calculate dynamic font size based on number of players
        function calculateDynamicFontSize(totalSections) {
            // Scale down font size as number of players increases
            if (totalSections <= 4) {
                return '1.4rem'; // Large font for 4 or fewer players
            } else if (totalSections <= 8) {
                return '1.2rem'; // Medium font for 5-8 players
            } else if (totalSections <= 12) {
                return '1.0rem'; // Smaller font for 9-12 players
            } else if (totalSections <= 16) {
                return '0.9rem'; // Even smaller for 13-16 players
            } else if (totalSections <= 20) {
                return '0.8rem'; // Small font for 17-20 players
            } else if (totalSections <= 24) {
                return '0.7rem'; // Very small for 21-24 players
            } else {
                // For more than 24 players, use a very small font
                return '0.6rem';
            }
        }
        
        // Calculate optimal text radius based on number of players
        function calculateTextRadius(totalSections, baseRadius) {
            // Adjust text radius based on number of players for better positioning
            if (totalSections <= 4) {
                return baseRadius * 0.7; // Closer to center for fewer players
            } else if (totalSections <= 8) {
                return baseRadius * 0.6; // Standard position
            } else if (totalSections <= 12) {
                return baseRadius * 0.55; // Slightly closer to center
            } else if (totalSections <= 16) {
                return baseRadius * 0.5; // Closer to center for more players
            } else {
                return baseRadius * 0.45; // Very close to center for many players
            }
        }
        
        // Update roulette wheel with current players using perfect SVG alignment
        function updateRouletteWheel() {
            const wheel = document.getElementById('rouletteWheel');
            const totalSections = players.length;
            
            if (!wheel) {
                return;
            }
            
            // Clear any existing sections
            wheel.innerHTML = '<div class="wheel-center" id="wheelCenter" onclick="spinWheel()"><i class="fas fa-play" id="centerIcon"></i></div>';
            wheelSections = [];
            
            // If no players, show empty wheel message
            if (totalSections === 0) {
                wheel.innerHTML = `
                    <div class="wheel-center" id="wheelCenter" onclick="spinWheel()"><i class="fas fa-play" id="centerIcon"></i></div>
                `;
                return;
            }
            
            // Create SVG for perfect pie slices
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
            
            // Create sections with perfect alignment
            for (let i = 0; i < totalSections; i++) {
                const startAngle = i * anglePerSection;
                const endAngle = (i + 1) * anglePerSection;
                
                // Create SVG path for this slice with perfect alignment
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
                
                // Create SVG text element for perfect positioning
                const textElement = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                
                // Truncate player name if too long (max 20 characters)
                let playerName = players[i] || `Player ${i + 1}`;
                const maxLength = 20;
                if (playerName.length > maxLength) {
                    playerName = playerName.substring(0, maxLength) + '...';
                }
                textElement.textContent = playerName;
                
                // Calculate perfect text position along slice arc (moved up)
                const textAngle = startAngle + (anglePerSection / 2);
                
                // Dynamic text radius adjustment based on number of entries
                let radiusAdjustment = 5; // Default for 1-20 entries
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
                
                // Set text attributes for perfect positioning with 90-degree rotation
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
            
            // Ensure slow spin continues after wheel update
            if (!isSpinning) {
                wheel.classList.add('slow-spin');
            }
        }
        
        function spinWheel() {
            if (isSpinning || players.length === 0) return;
            
            // Validate spinning time before spinning
            const currentTime = parseInt(document.getElementById('spinningTimeInput').value);
            if (!validateSpinningTime(currentTime)) {
                alert('Please enter a valid spinning time between 1 and 60 seconds.');
                return;
            }
            
            isSpinning = true;
            const wheelCenter = document.getElementById('wheelCenter');
            const centerIcon = document.getElementById('centerIcon');
            const wheel = document.getElementById('rouletteWheel');
            const winnerAnnouncement = document.getElementById('winnerAnnouncement');
            
            wheelCenter.disabled = true;
            centerIcon.className = 'fas fa-spinner fa-spin';
            
            // Hide previous winner announcement
            winnerAnnouncement.classList.remove('show');
            
            // Remove slow spin and add spinning class
            wheel.classList.remove('slow-spin');
            wheel.classList.add('spinning');
            
            // Calculate random extra spins (3-8 full rotations)
            const extraSpins = Math.random() * 5 + 3;
            
            // Adjust rotation amount based on spinning time to maintain consistent visual speed
            // Base speed is for 4 seconds, so multiply by spinningTime/4 to maintain same visual speed
            const speedMultiplier = spinningTime / 4;
            const extraRotation = extraSpins * 360 * speedMultiplier;
            
            // Calculate final rotation (just extra spins, winner determined by final position)
            const finalRotation = extraRotation;
            
            // Set CSS variables for animation
            wheel.style.setProperty('--spin-rotation', `${finalRotation}deg`);
            wheel.style.setProperty('--spin-duration', `${spinningTime}s`);
            
            // Add blur effect 2 seconds before spinning ends (only if spinning time > 2 seconds)
            if (spinningTime > 2) {
                const blurTimeout = setTimeout(() => {
                    wheel.classList.add('blur-effect');
                }, (spinningTime - 2) * 1000);
            } else {
                // If spinning time is 2 seconds or less, add blur immediately
                wheel.classList.add('blur-effect');
            }
            
            // Show winner after animation completes (use custom spinning time)
            setTimeout(() => {
                // Calculate winner based on final wheel position
                const actualWinner = calculateWinnerFromPosition(finalRotation);
                showWinner(actualWinner.winner, actualWinner.winnerNumber);
                createConfetti();
            }, spinningTime * 1000);
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
            const winnerPopupOverlay = document.getElementById('winnerPopupOverlay');
            const winnerAnnouncement = document.getElementById('winnerAnnouncement');
            
            winnerName.textContent = winner;
            
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
        
        function copyWinnerName() {
            const winnerName = document.getElementById('winnerName');
            const copyBtn = document.querySelector('.copy-btn');
            const copyIcon = copyBtn.querySelector('i');
            
            // Copy to clipboard
            navigator.clipboard.writeText(winnerName.textContent).then(() => {
                // Visual feedback
                copyBtn.classList.add('copied');
                copyIcon.className = 'fas fa-check';
                
                // Reset after 2 seconds
                setTimeout(() => {
                    copyBtn.classList.remove('copied');
                    copyIcon.className = 'fas fa-copy';
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = winnerName.textContent;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                // Visual feedback for fallback
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
            const centerIcon = document.getElementById('centerIcon');
            const wheel = document.getElementById('rouletteWheel');
            
            isSpinning = false;
            wheelCenter.disabled = false;
            centerIcon.className = 'fas fa-play';
            wheel.classList.remove('spinning');
            wheel.classList.remove('blur-effect');
            
            // Always restart slow spin (continuous animation)
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
        
        function showDebugInfo() {
            // Get the Next to Win data from session
            fetch('{{ route("admin.debug.next.to.win") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    players: players
                })
            })
            .then(response => response.json())
            .then(data => {
                let debugInfo = '=== DEBUG: Next to Win vs Player List ===\n\n';
                
                // Summary
                debugInfo += `📊 SUMMARY:\n`;
                debugInfo += `Next to Win entries: ${data.count}\n`;
                debugInfo += `Current players: ${data.playerCount}\n`;
                debugInfo += `Available in players: ${data.availableCount}\n`;
                debugInfo += `NOT in players: ${data.notAvailableCount}\n\n`;
                
                if (data.nextToWin && data.nextToWin.length > 0) {
                    debugInfo += `=== NEXT TO WIN LIST ===\n\n`;
                    
                    data.comparison.forEach((item) => {
                        const status = item.is_in_players ? '✅ IN PLAYERS' : '❌ NOT IN PLAYERS';
                        debugInfo += `${item.index}. "${item.name}" ${status}\n`;
                        debugInfo += `   Added by: ${item.added_by}\n`;
                        debugInfo += `   Added at: ${item.added_at}\n\n`;
                    });
                    
                    if (data.availableInPlayers.length > 0) {
                        debugInfo += `=== AVAILABLE IN PLAYERS ===\n`;
                        debugInfo += data.availableInPlayers.join(', ') + '\n\n';
                    }
                    
                    if (data.notInPlayers.length > 0) {
                        debugInfo += `=== NOT IN PLAYERS ===\n`;
                        debugInfo += data.notInPlayers.join(', ') + '\n\n';
                    }
                } else {
                    debugInfo += 'No entries in Next to Win list.\n\n';
                }
                
                if (data.players && data.players.length > 0) {
                    debugInfo += `=== CURRENT PLAYER LIST ===\n`;
                    debugInfo += data.players.join(', ') + '\n\n';
                } else {
                    debugInfo += 'No players in current game.\n\n';
                }
                
                debugInfo += '=== Raw JSON Data ===\n';
                debugInfo += JSON.stringify(data, null, 2);
                
                // Show in a modal-like alert
                alert(debugInfo);
                
                // Also log to console for developers
                console.log('=== DEBUG: Next to Win vs Players ===', data);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error fetching debug information: ' + error.message);
            });
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
            
            // Settings event listeners
            const spinningTimeInput = document.getElementById('spinningTimeInput');
            const spinningTimeSlider = document.getElementById('spinningTimeSlider');
            
            spinningTimeInput.addEventListener('input', function() {
                const time = parseInt(this.value);
                updateSpinningTime(time);
            });
            
            // Add validation on blur (when user leaves the field)
            spinningTimeInput.addEventListener('blur', function() {
                const time = parseInt(this.value);
                if (isNaN(time) || time < 1 || time > 60) {
                    // Reset to valid value if invalid
                    this.value = spinningTime;
                    validateSpinningTime(spinningTime);
                }
            });
            
            spinningTimeSlider.addEventListener('input', function() {
                updateSpinningTime(parseInt(this.value));
            });
            
            // Close settings when clicking outside
            document.addEventListener('click', function(event) {
                const settingsPanel = document.getElementById('settingsPanel');
                const settingsBtn = document.getElementById('settingsBtn');
                
                if (settingsVisible && 
                    !settingsPanel.contains(event.target) && 
                    !settingsBtn.contains(event.target)) {
                    closeSettings();
                }
            });
            
            // Initialize player list as visible by default
            const playerSection = document.getElementById('playerSection');
            const showToggleBtn = document.getElementById('showToggleBtn');
            
            // Ensure player list starts visible
            playerListVisible = true;
            playerSection.classList.remove('collapsed');
            showToggleBtn.classList.remove('visible');
            
            // Load saved settings
            loadSettings();
            
            // Auto-focus the textarea
            playersTextarea.focus();
            
            // Initialize with empty player list and show empty wheel
            players = [];
            updateRouletteWheel();
            hideGameControls();
            
            // Ensure slow spin is active (it's already in HTML, but make sure it's not removed)
            const wheel = document.getElementById('rouletteWheel');
            if (wheel && !isSpinning) {
                wheel.classList.add('slow-spin');
            }
        });
    </script>
</body>
</html>