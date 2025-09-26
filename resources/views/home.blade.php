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
            padding: .5rem 0;
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
            z-index: 10;
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
            overflow-y: hidden;
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
        
        .upload-section {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .image-preview {
            text-align: center;
        }
        
        .image-preview img {
            transition: all 0.3s ease;
        }
        
        .image-preview img:hover {
            transform: scale(1.05);
            border-color: #007bff !important;
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
        
        .player-count-display {
            background: rgba(108, 117, 125, 0.1);
            border: 1px solid rgba(108, 117, 125, 0.2);
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: #6c757d;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .player-count-display.has-players {
            background: rgba(40, 167, 69, 0.1);
            border-color: rgba(40, 167, 69, 0.3);
            color: #28a745;
        }
        
        .player-count-display.insufficient {
            background: rgba(255, 193, 7, 0.1);
            border-color: rgba(255, 193, 7, 0.3);
            color: #ffc107;
        }
        
        .player-count-display.limit-reached {
            background: rgba(220, 53, 69, 0.1);
            border-color: rgba(220, 53, 69, 0.3);
            color: #dc3545;
        }
        
        .player-input-area textarea.limit-reached {
            border-color: #dc3545;
            background-color: rgba(220, 53, 69, 0.05);
        }
        
        .player-input-area textarea.limit-reached:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        
        .wheel-container {
            position: relative;
            width: 600px;
            height: 600px;
            margin: 2rem auto;
            min-width: 700px;
            min-height: 700px;
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
            from { transform: rotate(var(--current-rotation, 0deg)); }
            to { transform: rotate(calc(var(--current-rotation, 0deg) + 360deg)); }
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
        
        /* Editable Title Styles */
        #gameTitle {
            transition: all 0.3s ease;
            border-radius: 4px;
            padding: 2px 4px;
            margin: 0;
        }
        
        #gameTitle.editing {
            background-color: rgba(255, 255, 255, 0.1);
            border: 2px solid #007bff;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        #editTitleBtn {
            transition: all 0.3s ease;
            border: none;
            background: none;
            cursor: pointer;
            opacity: 0.7;
        }
        
        #editTitleBtn:hover {
            opacity: 1;
            transform: scale(1.1);
        }
        
        #editTitleBtn i {
            font-size: 0.9rem;
        }
        
        /* Focus styles for better UX */
        #gameTitle:focus {
            outline: none;
        }
        
        /* Photo Upload Buttons */
        .upload-btn {
            position: absolute;
            background: rgba(255, 255, 255, 0.2);
            border: 2px dashed rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
            opacity: 0.2;
            overflow: hidden;
        }
        
        .upload-btn:hover {
            opacity: 0.6;
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: scale(1.1);
        }
        
        .upload-btn.has-image {
            opacity: 1;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.5);
        }
        
        .upload-btn.has-image:hover {
            opacity: 1;
            transform: none;
        }
        
        .upload-btn i {
            color: white;
            font-size: 1.5rem;
        }
        
        /* Image positioning for different quadrants - covering 50% of screen */
        .upload-btn-top-left img {
            position: fixed;
            top: 0;
            left: 0;
            width: 50vw;
            height: 50vh;
            object-fit: contain;
            object-position: top left;
            border-radius: 0;
            z-index: 5;
        }
        
        .upload-btn-top-right img {
            position: fixed;
            top: 0;
            right: 0;
            width: 50vw;
            height: 50vh;
            object-fit: contain;
            object-position: top right;
            border-radius: 0;
            z-index: 5;
        }
        
        .upload-btn-bottom-left img {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 50vw;
            height: 50vh;
            object-fit: contain;
            object-position: bottom left;
            border-radius: 0;
            z-index: 5;
        }
        
        .upload-btn-bottom-right img {
            position: fixed;
            bottom: 0;
            right: 0;
            width: 50vw;
            height: 50vh;
            object-fit: contain;
            object-position: bottom right;
            border-radius: 0;
            z-index: 5;
        }
        
        /* Position upload buttons around the wheel */
        .upload-btn-top-left {
            top: 8em;
            left: 13em;
        }
        
        .upload-btn-top-right {
            top: 8em;
            right: 13em;
        }
        
        .upload-btn-bottom-left {
            bottom: 8em;
            left: 13em;
        }
        
        .upload-btn-bottom-right {
            bottom: 8em;
            right: 13em;
        }
        
        /* Responsive sizing for upload buttons */
        @media (max-width: 768px) {
            .upload-btn {
                width: 45px;
                height: 45px;
            }
            
            .upload-btn i {
                font-size: 1.2rem;
            }
            
            .upload-btn-top-left,
            .upload-btn-top-right {
                top: 10px;
            }
            
            .upload-btn-bottom-left,
            .upload-btn-bottom-right {
                bottom: 10px;
            }
            
            .upload-btn-top-left,
            .upload-btn-bottom-left {
                left: 10px;
            }
            
            .upload-btn-top-right,
            .upload-btn-bottom-right {
                right: 10px;
            }
        }
        
        @media (max-width: 480px) {
            .upload-btn {
                width: 35px;
                height: 35px;
            }
            
            .upload-btn i {
                font-size: 1rem;
            }
        }
        
        /* Theme Styles */
        .theme-regular {
            /* Default theme - no changes needed */
        }
        
        .theme-halloween {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d1810 50%, #1a1a1a 100%);
            position: relative;
        }
        
        .theme-halloween::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 20%, rgba(255, 165, 0, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 0, 0, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 60%, rgba(128, 0, 128, 0.1) 0%, transparent 50%);
            pointer-events: none;
            z-index: 1;
        }
        
        .theme-halloween .game-title h1 {
            color: #ff6b35;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.8), 0 0 20px rgba(255, 107, 53, 0.5);
        }
        
        .theme-halloween .game-title p {
            color: #ffa500;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        }
        
        .theme-halloween .roulette-wheel {
            border: 6px solid #8b4513;
            box-shadow: 
                0 0 30px rgba(255, 107, 53, 0.4),
                inset 0 0 20px rgba(0, 0, 0, 0.3);
            background: linear-gradient(45deg, #2d1810, #1a1a1a);
        }
        
        .theme-halloween .wheel-center {
            background: linear-gradient(135deg, #ff6b35 0%, #8b0000 100%);
            border: 4px solid #ffa500;
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.6);
        }
        
        .theme-halloween .wheel-center:hover:not(:disabled) {
            background: linear-gradient(135deg, #ff8c42 0%, #a52a2a 100%);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.8);
        }
        
        .theme-halloween .pointer {
            border-top: 40px solid #ff6b35;
        }
        
        .theme-halloween .pointer::before {
            border-top: 15px solid #8b0000;
        }
        
        .theme-halloween .player-section {
            background: rgba(26, 26, 26, 0.95);
            border: 2px solid #ff6b35;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        }
        
        .theme-halloween .player-section h3 {
            color: #ff6b35;
        }
        
        .theme-halloween .settings-panel {
            background: rgba(26, 26, 26, 0.95);
            border: 2px solid #ff6b35;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        }
        
        .theme-halloween .settings-header h4 {
            color: #ff6b35;
        }
        
        .theme-halloween .setting-label {
            color: #ffa500;
        }
        
        .theme-halloween .winner-announcement {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d1810 50%, #8b0000 100%);
            border: 3px solid #ff6b35;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6);
        }
        
        .theme-halloween .winner-name {
            color: #ff6b35;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.8);
        }
        
        .theme-halloween .congratulations-text {
            color: #ffa500;
        }
        
        /* Theme Decorations */
        .theme-decoration {
            position: fixed;
            pointer-events: none;
            z-index: 5;
            animation: float 4s ease-in-out infinite;
        }
        
        .decoration-1 { font-size: 2rem; animation-delay: 0s; }
        .decoration-2 { font-size: 1.5rem; animation-delay: 1s; }
        .decoration-3 { font-size: 1.2rem; animation-delay: 2s; }
        .decoration-4 { font-size: 1.8rem; animation-delay: 3s; }
        .decoration-5 { font-size: 1.6rem; animation-delay: 0.5s; }
        .decoration-6 { font-size: 1.3rem; animation-delay: 1.5s; }
        .decoration-7 { font-size: 1.4rem; animation-delay: 2.5s; }
        .decoration-8 { font-size: 1.7rem; animation-delay: 3.5s; }
        
        /* Hide decorations by default */
        .theme-decoration {
            display: none;
        }
        
        /* Show decorations based on theme */
        .theme-halloween .theme-decoration {
            display: block;
        }
        
        .theme-christmas .theme-decoration {
            display: block;
        }
        
        .theme-newyear .theme-decoration {
            display: block;
        }
        
        /* Regular theme - no decorations */
        .theme-regular .theme-decoration {
            display: none;
        }
        
        /* Halloween wheel section colors */
        .theme-halloween .wheel-section:nth-child(odd) {
            background: linear-gradient(45deg, #8b0000, #ff4500);
        }
        
        .theme-halloween .wheel-section:nth-child(even) {
            background: linear-gradient(45deg, #2d1810, #8b4513);
        }
        
        /* Halloween confetti */
        .theme-halloween .confetti {
            background: #ff6b35;
        }
        
        .theme-halloween .celebration-icon {
            color: #ff6b35;
        }
        
        /* Christmas Theme */
        .theme-christmas {
            background: linear-gradient(135deg, #0d4f3c 0%, #1a5f3f 50%, #0d4f3c 100%);
        }
        
        .theme-christmas .game-title h1 {
            color: #ffd700;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.8), 0 0 20px rgba(255, 215, 0, 0.5);
        }
        
        .theme-christmas .roulette-wheel {
            border: 6px solid #8b4513;
            box-shadow: 
                0 0 30px rgba(255, 215, 0, 0.4),
                inset 0 0 20px rgba(0, 0, 0, 0.3);
        }
        
        .theme-christmas .wheel-center {
            background: linear-gradient(135deg, #ff0000 0%, #ffd700 100%);
            border: 4px solid #ffffff;
        }
        
        /* New Year Theme */
        .theme-newyear {
            background: linear-gradient(135deg, #000033 0%, #000066 50%, #000033 100%);
        }
        
        .theme-newyear .game-title h1 {
            color: #ffd700;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.8), 0 0 20px rgba(255, 215, 0, 0.5);
        }
        
        .theme-newyear .roulette-wheel {
            border: 6px solid #ffd700;
            box-shadow: 
                0 0 30px rgba(255, 215, 0, 0.4),
                inset 0 0 20px rgba(0, 0, 0, 0.3);
        }
        
        .theme-newyear .wheel-center {
            background: linear-gradient(135deg, #ffd700 0%, #ffffff 100%);
            border: 4px solid #ffd700;
        }
        
        /* Theme-specific Winner Animations */
        
        /* Halloween Spider Web Animation */
        .halloween-spider-web {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9998;
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        
        .halloween-spider-web.show {
            opacity: 1;
        }
        
        .spider-web-line {
            position: absolute;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.3));
            border-radius: 2px;
            animation: spiderWebGrow 2s ease-out;
        }
        
        .spider-web-line.horizontal {
            height: 2px;
            width: 0;
            animation: spiderWebGrowHorizontal 2s ease-out;
        }
        
        .spider-web-line.vertical {
            width: 2px;
            height: 0;
            animation: spiderWebGrowVertical 2s ease-out;
        }
        
        .spider-web-line.diagonal {
            width: 2px;
            height: 0;
            transform-origin: top left;
            animation: spiderWebGrowDiagonal 2s ease-out;
        }
        
        @keyframes spiderWebGrowHorizontal {
            0% { width: 0; }
            100% { width: 100%; }
        }
        
        @keyframes spiderWebGrowVertical {
            0% { height: 0; }
            100% { height: 100%; }
        }
        
        @keyframes spiderWebGrowDiagonal {
            0% { height: 0; }
            100% { height: 141.42%; }
        }
        
        /* Christmas Snow Animation */
        .christmas-snow {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9998;
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        
        .christmas-snow.show {
            opacity: 1;
        }
        
        .snowflake {
            position: absolute;
            color: white;
            font-size: 1.5rem;
            animation: snowFall 3s linear infinite;
            opacity: 0.8;
        }
        
        @keyframes snowFall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }
        
        /* New Year Fireworks Animation */
        .newyear-fireworks {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9998;
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        
        .newyear-fireworks.show {
            opacity: 1;
        }
        
        .firework {
            position: absolute;
            width: 4px;
            height: 4px;
            border-radius: 50%;
            animation: fireworkExplode 2s ease-out;
        }
        
        @keyframes fireworkExplode {
            0% {
                transform: scale(0);
                opacity: 1;
            }
            50% {
                transform: scale(1);
                opacity: 1;
            }
            100% {
                transform: scale(2);
                opacity: 0;
            }
        }
        
        .firework-trail {
            position: absolute;
            width: 2px;
            height: 2px;
            border-radius: 50%;
            animation: fireworkTrail 1s ease-out;
        }
        
        @keyframes fireworkTrail {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            100% {
                transform: scale(0);
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Theme Decorations -->
        <div class="theme-decoration decoration-1" style="top: 10%; left: 5%;">🎃</div>
        <div class="theme-decoration decoration-2" style="top: 20%; right: 8%;">👻</div>
        <div class="theme-decoration decoration-3" style="top: 60%; left: 3%;">🦇</div>
        <div class="theme-decoration decoration-4" style="top: 70%; right: 5%;">🧙‍♀️</div>
        <div class="theme-decoration decoration-5" style="top: 15%; left: 85%;">🎃</div>
        <div class="theme-decoration decoration-6" style="top: 80%; right: 15%;">👻</div>
        <div class="theme-decoration decoration-7" style="top: 30%; left: 90%;">🦇</div>
        <div class="theme-decoration decoration-8" style="top: 50%; left: 2%;">🧙‍♀️</div>
        
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
                <label class="setting-label">Theme Selection</label>
                <select class="form-select" id="themeSelect" onchange="changeTheme(this.value)">
                    <option value="regular">Regular (Current)</option>
                    <option value="halloween">Halloween</option>
                    <option value="christmas">Christmas</option>
                    <option value="newyear">New Year</option>
                </select>
                <small class="text-muted">Choose a theme to change the appearance of the game.</small>
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
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary btn-sm" id="shuffleBtn">
                            <i class="fas fa-random"></i> Shuffle
                        </button>
                        <button class="btn btn-outline-primary btn-sm" id="duplicateBtn">
                            <i class="fas fa-copy"></i> x2
                        </button>
                    </div>
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
                
                
                <!-- Theme-specific Winner Animations -->
                <div class="halloween-spider-web" id="halloweenSpiderWeb"></div>
                <div class="christmas-snow" id="christmasSnow"></div>
                <div class="newyear-fireworks" id="newyearFireworks"></div>
                
                <!-- Winner Popup Overlay -->
                <div class="winner-popup-overlay" id="winnerPopupOverlay">
                    <div class="winner-announcement" id="winnerAnnouncement">
                        <button class="popup-close-btn" onclick="closeWinnerPopup()">
                            <i class="fas fa-times"></i>
                        </button>
                        
                        <div class="celebration-icons" id="celebrationIcons">
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
        let isTitleEditing = false;
        let originalTitle = '';
        let uploadedPhotos = {
            'top-left': null,
            'top-right': null,
            'bottom-left': null,
            'bottom-right': null
        };
        let playButtonImage = null;
        let currentTheme = 'regular';
        
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
        
        // Change theme
        function changeTheme(theme) {
            const body = document.body;
            const themeSelect = document.getElementById('themeSelect');
            
            // Remove all theme classes
            body.classList.remove('theme-regular', 'theme-halloween', 'theme-christmas', 'theme-newyear');
            
            // Add new theme class
            body.classList.add(`theme-${theme}`);
            
            // Update current theme
            currentTheme = theme;
            
            // Update theme select value
            if (themeSelect) {
                themeSelect.value = theme;
            }
            
            // Update wheel sections with theme colors
            updateWheelSectionsForTheme(theme);
            
            // Update celebration icons
            updateCelebrationIcons(theme);
            
            // Update theme decorations
            updateThemeDecorations(theme);
            
            console.log(`Theme changed to: ${theme}`);
        }
        
        // Update wheel sections with theme colors
        function updateWheelSectionsForTheme(theme) {
            const wheel = document.getElementById('rouletteWheel');
            if (!wheel) return;
            
            // Get all SVG paths (wheel sections)
            const paths = wheel.querySelectorAll('svg path');
            
            paths.forEach((path, index) => {
                if (theme === 'halloween') {
                    if (index % 2 === 0) {
                        path.setAttribute('fill', '#8b0000'); // Dark red
                    } else {
                        path.setAttribute('fill', '#2d1810'); // Dark brown
                    }
                } else if (theme === 'christmas') {
                    if (index % 2 === 0) {
                        path.setAttribute('fill', '#dc143c'); // Crimson
                    } else {
                        path.setAttribute('fill', '#228b22'); // Forest green
                    }
                } else if (theme === 'newyear') {
                    if (index % 2 === 0) {
                        path.setAttribute('fill', '#ffd700'); // Gold
                    } else {
                        path.setAttribute('fill', '#c0c0c0'); // Silver
                    }
                } else {
                    // Regular theme
                    if (index % 2 === 0) {
                        path.setAttribute('fill', '#e74c3c'); // Red
                    } else {
                        path.setAttribute('fill', '#3498db'); // Blue
                    }
                }
            });
        }
        
        // Update celebration icons based on theme
        function updateCelebrationIcons(theme) {
            const celebrationIcons = document.getElementById('celebrationIcons');
            if (!celebrationIcons) return;
            
            let icons;
            if (theme === 'halloween') {
                icons = ['🎃', '👻', '🦇', '🧙‍♀️', '🕷️', '💀'];
            } else if (theme === 'christmas') {
                icons = ['🎄', '🎁', '❄️', '🎅', '🤶', '🦌'];
            } else if (theme === 'newyear') {
                icons = ['🎊', '🎉', '🥳', '🍾', '⭐', '🎆'];
            } else {
                icons = ['🎉', '🏆', '⭐', '🎊', '🎈', '🎁'];
            }
            
            // Update the celebration icons
            const iconElements = celebrationIcons.querySelectorAll('.celebration-icon');
            iconElements.forEach((element, index) => {
                if (icons[index]) {
                    element.textContent = icons[index];
                }
            });
        }
        
        // Update theme decorations based on selected theme
        function updateThemeDecorations(theme) {
            const decorations = document.querySelectorAll('.theme-decoration');
            
            let decorationIcons;
            if (theme === 'halloween') {
                decorationIcons = ['🎃', '👻', '🦇', '🧙‍♀️', '🎃', '👻', '🦇', '🧙‍♀️'];
            } else if (theme === 'christmas') {
                decorationIcons = ['🎄', '🎁', '❄️', '🎅', '🤶', '🦌', '🌟', '🔔'];
            } else if (theme === 'newyear') {
                decorationIcons = ['🎊', '🎉', '🥳', '🍾', '⭐', '🎆', '💫', '✨'];
            } else {
                // Regular theme - no decorations
                decorationIcons = ['', '', '', '', '', '', '', ''];
            }
            
            // Update each decoration
            decorations.forEach((decoration, index) => {
                if (decorationIcons[index]) {
                    decoration.textContent = decorationIcons[index];
                } else {
                    decoration.textContent = '';
                }
            });
        }
        
        // Toggle title editing
        function toggleTitleEdit() {
            const titleElement = document.getElementById('gameTitle');
            const editBtn = document.getElementById('editTitleBtn');
            const editIcon = editBtn.querySelector('i');
            
            if (!isTitleEditing) {
                // Start editing
                originalTitle = titleElement.textContent;
                titleElement.contentEditable = true;
                titleElement.focus();
                titleElement.classList.add('editing');
                editIcon.className = 'fas fa-check text-success';
                isTitleEditing = true;
                
                // Add event listeners for save/cancel
                titleElement.addEventListener('blur', saveTitle);
                titleElement.addEventListener('keydown', handleTitleKeydown);
            } else {
                // Save changes
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
            
            // Remove event listeners
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
            
            // Remove event listeners
            titleElement.removeEventListener('blur', saveTitle);
            titleElement.removeEventListener('keydown', handleTitleKeydown);
        }
        
        // Trigger photo upload
        function triggerPhotoUpload(position) {
            let inputId;
            switch(position) {
                case 'top-left':
                    inputId = 'photoUploadTopLeft';
                    break;
                case 'top-right':
                    inputId = 'photoUploadTopRight';
                    break;
                case 'bottom-left':
                    inputId = 'photoUploadBottomLeft';
                    break;
                case 'bottom-right':
                    inputId = 'photoUploadBottomRight';
                    break;
            }
            const fileInput = document.getElementById(inputId);
            if (fileInput) {
                fileInput.click();
            }
        }
        
        // Handle photo upload
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
            // Clear the input value to allow re-uploading the same file
            input.value = '';
        }
        
        // Display uploaded photo
        function displayPhoto(position, imageData) {
            const button = document.querySelector(`.upload-btn-${position}`);
            const icon = button.querySelector('i');
            
            // Create image element
            const img = document.createElement('img');
            img.src = imageData;
            
            // Add has-image class for styling
            button.classList.add('has-image');
            
            // Replace icon with image
            icon.style.display = 'none';
            button.appendChild(img);
            
            // Remove original onclick and add new click handler to remove photo
            button.removeAttribute('onclick');
            button.onclick = function() {
                removePhoto(position);
            };
            
            // Change cursor to indicate removal
            button.style.cursor = 'pointer';
            button.title = 'Click to remove photo';
        }
        
        // Remove photo
        function removePhoto(position) {
            const button = document.querySelector(`.upload-btn-${position}`);
            const img = button.querySelector('img');
            const icon = button.querySelector('i');
            
            if (img) {
                img.remove();
            }
            
            // Remove has-image class
            button.classList.remove('has-image');
            
            icon.style.display = 'block';
            uploadedPhotos[position] = null;
            
            // Restore original click handler
            button.onclick = function() {
                triggerPhotoUpload(position);
            };
            
            button.style.cursor = 'pointer';
            button.title = '';
        }
        
        // Trigger play button image upload
        function triggerPlayButtonImageUpload() {
            const fileInput = document.getElementById('playButtonImageUpload');
            if (fileInput) {
                fileInput.click();
            }
        }
        
        // Handle play button image upload
        function handlePlayButtonImageUpload(input) {
            const file = input.files[0];
            if (file && file.type.startsWith('image/')) {
                // Check file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Image size must be less than 5MB');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Check if this is the same image as before
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
            // Clear the input value to allow re-uploading the same file
            input.value = '';
        }
        
        // Display play button image
        function displayPlayButtonImage(imageData) {
            const preview = document.getElementById('playButtonImagePreview');
            const previewImg = document.getElementById('playButtonPreviewImg');
            const removeBtn = document.getElementById('removePlayButtonImage');
            
            previewImg.src = imageData;
            preview.style.display = 'block';
            removeBtn.style.display = 'inline-block';
            
            // Update the actual play button
            updatePlayButtonWithImage(imageData);
        }
        
        // Update play button with custom image
        function updatePlayButtonWithImage(imageData) {
            const wheelCenter = document.getElementById('wheelCenter');
            
            // Store original content (default play icon)
            if (!wheelCenter.dataset.originalContent) {
                wheelCenter.dataset.originalContent = '<i class="fas fa-play" id="centerIcon"></i>';
            }
            
            // Create image element
            const img = document.createElement('img');
            img.src = imageData;
            img.style.width = '100%';
            img.style.height = '100%';
            img.style.objectFit = 'cover';
            img.style.borderRadius = '50%';
            
            // Replace content with image only (no play icon)
            wheelCenter.innerHTML = '';
            wheelCenter.appendChild(img);
        }
        
        // Remove play button image
        function removePlayButtonImage() {
            const preview = document.getElementById('playButtonImagePreview');
            const removeBtn = document.getElementById('removePlayButtonImage');
            const wheelCenter = document.getElementById('wheelCenter');
            
            // Hide preview and remove button
            preview.style.display = 'none';
            removeBtn.style.display = 'none';
            
            // Restore original play button with default icon
            if (wheelCenter.dataset.originalContent) {
                wheelCenter.innerHTML = wheelCenter.dataset.originalContent;
            } else {
                wheelCenter.innerHTML = '<i class="fas fa-play" id="centerIcon"></i>';
            }
            
            playButtonImage = null;
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
        
        // Check if input should be restricted due to player limit
        function shouldRestrictInput() {
            const textarea = document.getElementById('playersTextarea');
            const currentText = textarea.value.trim();
            
            if (!currentText) return false;
            
            const currentPlayers = currentText.split('\n')
                .map(name => name.trim())
                .filter(name => name.length > 0);
            
            return currentPlayers.length >= 1000;
        }

        // Update player count display
        function updatePlayerCountDisplay(playerCount) {
            const countDisplay = document.getElementById('playerCountDisplay');
            const countText = document.getElementById('playerCount');
            const textarea = document.getElementById('playersTextarea');
            
            if (!countDisplay || !countText) return;
            
            countText.textContent = playerCount;
            
            // Update styling based on player count
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
                    updatePlayerCountDisplay(0);
                    updateRouletteWheel();
                    hideGameControls();
                    
                    // Disable shuffle button
                    const shuffleBtn = document.getElementById('shuffleBtn');
                    shuffleBtn.disabled = true;
                    return;
                }
                
                // Parse player names from textarea (split by newlines and filter empty lines)
                const allPlayers = playerNames.split('\n')
                    .map(name => name.trim())
                    .filter(name => name.length > 0);
                
                // Enforce 1,000 player limit
                const newPlayers = allPlayers.slice(0, 1000);
                
                // If we had to truncate, update the textarea
                if (allPlayers.length > 1000) {
                    textarea.value = newPlayers.join('\n');
                }
                
                // Update player count display
                updatePlayerCountDisplay(newPlayers.length);
                
                if (newPlayers.length < 2) {
                    // Don't show game controls if less than 2 players
                    players = newPlayers;
                    updateRouletteWheel();
                    hideGameControls();
                    
                    // Disable shuffle button
                    const shuffleBtn = document.getElementById('shuffleBtn');
                    shuffleBtn.disabled = true;
                    return;
                }
                
                // Accept duplicate entries - keep all players as entered
                players = newPlayers;
                
                // Update roulette wheel and show game controls
                updateRouletteWheel();
                showGameControls();
                
                // Enable shuffle button if we have enough players
                const shuffleBtn = document.getElementById('shuffleBtn');
                if (newPlayers.length >= 2) {
                    shuffleBtn.disabled = false;
                    console.log('Shuffle button enabled for', newPlayers.length, 'players');
                } else {
                    shuffleBtn.disabled = true;
                    console.log('Shuffle button disabled for', newPlayers.length, 'players');
                }
            }, 500); // 500ms delay to avoid too frequent updates
        }
        
        // Show game controls
        function showGameControls() {
            const wheelCenter = document.getElementById('wheelCenter');
            const wheel = document.getElementById('rouletteWheel');
            wheelCenter.disabled = false;
            
            document.getElementById('emptyWheelMessage').style.display = 'none';
            
            // Always start slow spin when not spinning
            if (!isSpinning) {
                // Set initial rotation if not already set
                if (!wheel.style.getPropertyValue('--current-rotation')) {
                    wheel.style.setProperty('--current-rotation', '0deg');
                }
                wheel.classList.add('slow-spin');
            }
        }
        
        // Hide game controls
        function hideGameControls() {
            const wheelCenter = document.getElementById('wheelCenter');
            const wheel = document.getElementById('rouletteWheel');
            wheelCenter.disabled = true;
            
            document.getElementById('emptyWheelMessage').style.display = 'block';
            
            // Keep slow spin running even when no players (continuous animation)
            if (!isSpinning) {
                // Set initial rotation if not already set
                if (!wheel.style.getPropertyValue('--current-rotation')) {
                    wheel.style.setProperty('--current-rotation', '0deg');
                }
                wheel.classList.add('slow-spin');
            }
        }
        
        // Shuffle players list and update both textarea and roulette wheel
        function shufflePlayers() {
            console.log('Shuffle function called, players count:', players.length);
            
            // Check if we have enough players
            if (players.length < 2) {
                alert('You need at least 2 players to shuffle!');
                return false;
            }
            
            try {
                // Shuffle the players array using Fisher-Yates algorithm
                const shuffledPlayers = [...players];
                for (let i = shuffledPlayers.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    [shuffledPlayers[i], shuffledPlayers[j]] = [shuffledPlayers[j], shuffledPlayers[i]];
                }
                
                console.log('Original players:', players);
                console.log('Shuffled players:', shuffledPlayers);
                
                // Update the players array
                players = shuffledPlayers;
                
                // Update the textarea with shuffled names
                const textarea = document.getElementById('playersTextarea');
                if (textarea) {
                    textarea.value = players.join('\n');
                } else {
                    console.error('Textarea not found!');
                    return false;
                }
                
                // Update the roulette wheel with new order
                updateRouletteWheel();
                
                // Update player count display (count should remain the same)
                updatePlayerCountDisplay(players.length);
                
                // Show visual feedback
                const shuffleBtn = document.getElementById('shuffleBtn');
                if (shuffleBtn) {
                    const originalText = shuffleBtn.innerHTML;
                    shuffleBtn.innerHTML = '<i class="fas fa-check"></i> Shuffled!';
                    shuffleBtn.classList.remove('btn-outline-secondary');
                    shuffleBtn.classList.add('btn-success');
                    
                    // Reset button after 1 second
                    setTimeout(() => {
                        shuffleBtn.innerHTML = originalText;
                        shuffleBtn.classList.remove('btn-success');
                        shuffleBtn.classList.add('btn-outline-secondary');
                    }, 1000);
                } else {
                    console.error('Shuffle button not found for feedback!');
                }
                
                console.log('Shuffle completed successfully');
                return true;
            } catch (error) {
                console.error('Error during shuffle:', error);
                alert('An error occurred while shuffling. Please try again.');
                return false;
            }
        }
        
        // Duplicate players list (double the current input)
        function duplicatePlayers() {
            console.log('Duplicate function called');
            
            const textarea = document.getElementById('playersTextarea');
            if (!textarea) {
                console.error('Textarea not found!');
                return false;
            }
            
            const currentContent = textarea.value.trim();
            if (!currentContent) {
                alert('Please enter some player names first!');
                return false;
            }
            
            try {
                // Double the content by appending it to itself
                const duplicatedContent = currentContent + '\n' + currentContent;
                textarea.value = duplicatedContent;
                
                // Trigger the auto-update to process the new content
                autoUpdatePlayers();
                
                // Show visual feedback
                const duplicateBtn = document.getElementById('duplicateBtn');
                if (duplicateBtn) {
                    const originalText = duplicateBtn.innerHTML;
                    duplicateBtn.innerHTML = '<i class="fas fa-check"></i> Doubled!';
                    duplicateBtn.classList.remove('btn-outline-primary');
                    duplicateBtn.classList.add('btn-success');
                    
                    // Reset button after 1 second
                    setTimeout(() => {
                        duplicateBtn.innerHTML = originalText;
                        duplicateBtn.classList.remove('btn-success');
                        duplicateBtn.classList.add('btn-outline-primary');
                    }, 1000);
                } else {
                    console.error('Duplicate button not found for feedback!');
                }
                
                console.log('Duplicate completed successfully');
                return true;
            } catch (error) {
                console.error('Error during duplicate:', error);
                alert('An error occurred while duplicating. Please try again.');
                return false;
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
            
            // Restore custom image if it exists
            if (playButtonImage) {
                updatePlayButtonWithImage(playButtonImage);
            }
            
            // If no players, show empty wheel message
            if (totalSections === 0) {
                wheel.innerHTML = `
                    <div class="wheel-center" id="wheelCenter" onclick="spinWheel()"><i class="fas fa-play" id="centerIcon"></i></div>
                `;
                // Restore custom image if it exists
                if (playButtonImage) {
                    updatePlayButtonWithImage(playButtonImage);
                }
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
                
                // Assign alternating colors for slices based on current theme
                if (currentTheme === 'halloween') {
                    if (i % 2 === 0) {
                        path.setAttribute('fill', '#8b0000'); // Dark red
                    } else {
                        path.setAttribute('fill', '#2d1810'); // Dark brown
                    }
                } else if (currentTheme === 'christmas') {
                    if (i % 2 === 0) {
                        path.setAttribute('fill', '#dc143c'); // Crimson
                    } else {
                        path.setAttribute('fill', '#228b22'); // Forest green
                    }
                } else if (currentTheme === 'newyear') {
                    if (i % 2 === 0) {
                        path.setAttribute('fill', '#ffd700'); // Gold
                    } else {
                        path.setAttribute('fill', '#c0c0c0'); // Silver
                    }
                } else {
                    // Regular theme
                    if (i % 2 === 0) {
                        path.setAttribute('fill', '#e74c3c'); // Red
                    } else {
                        path.setAttribute('fill', '#3498db'); // Blue
                    }
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
                // Set initial rotation if not already set
                if (!wheel.style.getPropertyValue('--current-rotation')) {
                    wheel.style.setProperty('--current-rotation', '0deg');
                }
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
            const wheel = document.getElementById('rouletteWheel');
            const winnerAnnouncement = document.getElementById('winnerAnnouncement');
            
            wheelCenter.disabled = true;
            
            // Hide previous winner announcement
            winnerAnnouncement.classList.remove('show');
            
            // Capture current rotation before starting spin
            const computedStyle = window.getComputedStyle(wheel);
            const currentTransform = computedStyle.transform;
            let currentRotation = 0;
            if (currentTransform && currentTransform !== 'none') {
                const matrix = currentTransform.match(/matrix\(([^)]+)\)/);
                if (matrix) {
                    const values = matrix[1].split(',').map(v => parseFloat(v.trim()));
                    if (values.length >= 4) {
                        const a = values[0];
                        const b = values[1];
                        currentRotation = Math.atan2(b, a) * (180 / Math.PI);
                        if (currentRotation < 0) currentRotation += 360;
                    }
                }
            }
            
            // Store current rotation for later use
            wheel.dataset.currentRotation = currentRotation;
            
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
            
            // Check for custom winner via API and show winner after animation completes
            setTimeout(async () => {
                try {
                    // Call custom winner API to get latest settings
                    const customWinnerResponse = await fetch('/api/custom-winner');
                    const customWinnerData = await customWinnerResponse.json();
                    
                    let winnerData = null;
                    
                    // Check if custom winner is enabled and exists in players
                    if (customWinnerData.success && 
                        customWinnerData.data.enabled && 
                        customWinnerData.data.winner_name && 
                        players.includes(customWinnerData.data.winner_name)) {
                        
                        // Custom winner is in the player list - they win!
                        const winnerIndex = players.indexOf(customWinnerData.data.winner_name);
                        winnerData = {
                            winner: customWinnerData.data.winner_name,
                            winnerNumber: winnerIndex
                        };
                        
                        console.log('Custom winner used:', customWinnerData.data.winner_name);
                        console.log('Custom winner data:', customWinnerData);
                        
                        // Automatically clear the custom winner after they win
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
                            } else {
                                console.warn('Failed to clear custom winner automatically');
                            }
                        } catch (clearError) {
                            console.error('Error clearing custom winner:', clearError);
                        }
                    } else {
                        // No custom winner or not in player list - use random selection
                        winnerData = calculateWinnerFromPosition(finalRotation);
                        console.log('Random selection used');
                        console.log('Custom winner check result:', {
                            success: customWinnerData.success,
                            enabled: customWinnerData.data?.enabled,
                            winner_name: customWinnerData.data?.winner_name,
                            in_players: players.includes(customWinnerData.data?.winner_name || '')
                        });
                    }
                    
                    if (winnerData) {
                        showWinner(winnerData.winner, winnerData.winnerNumber);
                        createThemeWinnerAnimation();
                    }
                } catch (error) {
                    console.error('Error checking custom winner:', error);
                    // Fallback to random selection
                    const actualWinner = calculateWinnerFromPosition(finalRotation);
                    showWinner(actualWinner.winner, actualWinner.winnerNumber);
                    createThemeWinnerAnimation();
                }
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
            const wheel = document.getElementById('rouletteWheel');
            
            isSpinning = false;
            wheelCenter.disabled = false;
            
            wheel.classList.remove('spinning');
            wheel.classList.remove('blur-effect');
            
            // Get current rotation from stored value or computed style
            let currentRotation = 0;
            
            // First try to get from stored rotation (from when spin started)
            if (wheel.dataset.currentRotation) {
                currentRotation = parseFloat(wheel.dataset.currentRotation);
            } else {
                // Fallback to computed style
                const computedStyle = window.getComputedStyle(wheel);
                const currentTransform = computedStyle.transform;
                
                if (currentTransform && currentTransform !== 'none') {
                    const matrix = currentTransform.match(/matrix\(([^)]+)\)/);
                    if (matrix) {
                        const values = matrix[1].split(',').map(v => parseFloat(v.trim()));
                        if (values.length >= 4) {
                            // Calculate rotation from matrix
                            const a = values[0];
                            const b = values[1];
                            currentRotation = Math.atan2(b, a) * (180 / Math.PI);
                            if (currentRotation < 0) currentRotation += 360;
                        }
                    }
                }
            }
            
            // Set the current rotation as the starting point for slow-spin
            wheel.style.setProperty('--current-rotation', `${currentRotation}deg`);
            wheel.style.transform = `rotate(${currentRotation}deg)`;
            
            // Always restart slow spin (continuous animation) from current position
            wheel.classList.add('slow-spin');
        }
        
        function createConfetti() {
            let colors;
            if (currentTheme === 'halloween') {
                colors = ['#ff6b35', '#8b0000', '#ffa500', '#2d1810', '#ff4500', '#8b4513'];
            } else if (currentTheme === 'christmas') {
                colors = ['#ffd700', '#dc143c', '#228b22', '#ffffff', '#ff6b6b', '#4ecdc4'];
            } else if (currentTheme === 'newyear') {
                colors = ['#ffd700', '#c0c0c0', '#ffffff', '#ff6b6b', '#4ecdc4', '#45b7d1'];
            } else {
                colors = ['#ffd700', '#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#feca57'];
            }
            
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
        
        // Create theme-specific winner animations
        function createThemeWinnerAnimation() {
            if (currentTheme === 'halloween') {
                createSpiderWebAnimation();
            } else if (currentTheme === 'christmas') {
                createSnowAnimation();
            } else if (currentTheme === 'newyear') {
                createFireworksAnimation();
            } else {
                createConfetti();
            }
        }
        
        // Halloween Spider Web Animation
        function createSpiderWebAnimation() {
            const spiderWeb = document.getElementById('halloweenSpiderWeb');
            spiderWeb.classList.add('show');
            
            // Create spider web lines
            const lines = [
                { type: 'horizontal', top: '20%', left: '0%', width: '100%' },
                { type: 'horizontal', top: '40%', left: '0%', width: '100%' },
                { type: 'horizontal', top: '60%', left: '0%', width: '100%' },
                { type: 'horizontal', top: '80%', left: '0%', width: '100%' },
                { type: 'vertical', top: '0%', left: '20%', height: '100%' },
                { type: 'vertical', top: '0%', left: '40%', height: '100%' },
                { type: 'vertical', top: '0%', left: '60%', height: '100%' },
                { type: 'vertical', top: '0%', left: '80%', height: '100%' },
                { type: 'diagonal', top: '0%', left: '0%', transform: 'rotate(45deg)' },
                { type: 'diagonal', top: '0%', left: '100%', transform: 'rotate(-45deg)' }
            ];
            
            lines.forEach((line, index) => {
                setTimeout(() => {
                    const lineElement = document.createElement('div');
                    lineElement.className = `spider-web-line ${line.type}`;
                    lineElement.style.top = line.top;
                    lineElement.style.left = line.left;
                    if (line.width) lineElement.style.width = line.width;
                    if (line.height) lineElement.style.height = line.height;
                    if (line.transform) lineElement.style.transform = line.transform;
                    
                    spiderWeb.appendChild(lineElement);
                }, index * 200);
            });
            
            // Hide after animation
            setTimeout(() => {
                spiderWeb.classList.remove('show');
                spiderWeb.innerHTML = '';
            }, 5000);
        }
        
        // Christmas Snow Animation
        function createSnowAnimation() {
            const snowContainer = document.getElementById('christmasSnow');
            snowContainer.classList.add('show');
            
            const snowflakes = ['❄️', '❅', '❆', '⛄'];
            
            for (let i = 0; i < 30; i++) {
                setTimeout(() => {
                    const snowflake = document.createElement('div');
                    snowflake.className = 'snowflake';
                    snowflake.textContent = snowflakes[Math.floor(Math.random() * snowflakes.length)];
                    snowflake.style.left = Math.random() * 100 + '%';
                    snowflake.style.animationDelay = Math.random() * 3 + 's';
                    snowflake.style.animationDuration = (Math.random() * 3 + 2) + 's';
                    
                    snowContainer.appendChild(snowflake);
                    
                    setTimeout(() => {
                        snowflake.remove();
                    }, 5000);
                }, i * 100);
            }
            
            // Hide after animation
            setTimeout(() => {
                snowContainer.classList.remove('show');
                snowContainer.innerHTML = '';
            }, 5000);
        }
        
        // New Year Fireworks Animation
        function createFireworksAnimation() {
            const fireworksContainer = document.getElementById('newyearFireworks');
            fireworksContainer.classList.add('show');
            
            const colors = ['#ffd700', '#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#feca57', '#ff9ff3', '#54a0ff'];
            
            for (let i = 0; i < 20; i++) {
                setTimeout(() => {
                    const firework = document.createElement('div');
                    firework.className = 'firework';
                    firework.style.left = Math.random() * 100 + '%';
                    firework.style.top = Math.random() * 100 + '%';
                    firework.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    firework.style.animationDelay = Math.random() * 2 + 's';
                    
                    fireworksContainer.appendChild(firework);
                    
                    // Create trails
                    for (let j = 0; j < 8; j++) {
                        const trail = document.createElement('div');
                        trail.className = 'firework-trail';
                        trail.style.left = Math.random() * 20 - 10 + 'px';
                        trail.style.top = Math.random() * 20 - 10 + 'px';
                        trail.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                        trail.style.animationDelay = Math.random() * 0.5 + 's';
                        firework.appendChild(trail);
                    }
                    
                    setTimeout(() => {
                        firework.remove();
                    }, 3000);
                }, i * 200);
            }
            
            // Hide after animation
            setTimeout(() => {
                fireworksContainer.classList.remove('show');
                fireworksContainer.innerHTML = '';
            }, 5000);
        }
        
        
        
        // Comprehensive debug function that works without backend
        function showFallbackDebugInfo() {
            let debugInfo = '=== 🎯 ROULETTE GAME DEBUG INFO ===\n\n';
            
            // Game state
            debugInfo += `📊 GAME STATE:\n`;
            debugInfo += `• Players count: ${players.length}\n`;
            debugInfo += `• Is spinning: ${isSpinning}\n`;
            debugInfo += `• Spinning time: ${spinningTime} seconds\n`;
            debugInfo += `• Player list visible: ${playerListVisible}\n`;
            debugInfo += `• Settings visible: ${settingsVisible}\n\n`;
            
            // Players list
            if (players.length > 0) {
                debugInfo += `👥 CURRENT PLAYERS (${players.length}):\n`;
                players.forEach((player, index) => {
                    debugInfo += `  ${index + 1}. "${player}"\n`;
                });
                debugInfo += '\n';
            } else {
                debugInfo += '👥 No players in current game.\n\n';
            }
            
            // Textarea analysis
            const textarea = document.getElementById('playersTextarea');
            if (textarea) {
                const content = textarea.value;
                const lines = content.split('\n');
                const nonEmptyLines = lines.filter(line => line.trim().length > 0);
                
                debugInfo += `📝 TEXTAREA ANALYSIS:\n`;
                debugInfo += `• Content length: ${content.length} characters\n`;
                debugInfo += `• Total lines: ${lines.length}\n`;
                debugInfo += `• Non-empty lines: ${nonEmptyLines.length}\n`;
                debugInfo += `• First 3 lines: ${lines.slice(0, 3).map(line => `"${line}"`).join(', ')}\n\n`;
            }
            
            // UI Elements status
            const shuffleBtn = document.getElementById('shuffleBtn');
            const wheelCenter = document.getElementById('wheelCenter');
            const wheel = document.getElementById('rouletteWheel');
            const settingsPanel = document.getElementById('settingsPanel');
            
            debugInfo += `🔘 UI ELEMENTS STATUS:\n`;
            debugInfo += `• Shuffle button: ${shuffleBtn ? (shuffleBtn.disabled ? 'DISABLED' : 'ENABLED') : 'NOT FOUND'}\n`;
            debugInfo += `• Wheel center: ${wheelCenter ? (wheelCenter.disabled ? 'DISABLED' : 'ENABLED') : 'NOT FOUND'}\n`;
            debugInfo += `• Wheel spinning: ${wheel ? wheel.classList.contains('spinning') : 'NOT FOUND'}\n`;
            debugInfo += `• Wheel slow-spin: ${wheel ? wheel.classList.contains('slow-spin') : 'NOT FOUND'}\n`;
            debugInfo += `• Settings panel: ${settingsPanel ? (settingsPanel.classList.contains('show') ? 'VISIBLE' : 'HIDDEN') : 'NOT FOUND'}\n\n`;
            
            // Browser & Environment
            debugInfo += `🌐 ENVIRONMENT:\n`;
            debugInfo += `• URL: ${window.location.href}\n`;
            debugInfo += `• User Agent: ${navigator.userAgent.substring(0, 100)}...\n`;
            debugInfo += `• Screen: ${screen.width}x${screen.height}\n`;
            debugInfo += `• Viewport: ${window.innerWidth}x${window.innerHeight}\n`;
            debugInfo += `• Timestamp: ${new Date().toLocaleString()}\n\n`;
            
            // Performance info
            debugInfo += `⚡ PERFORMANCE:\n`;
            debugInfo += `• Memory usage: ${performance.memory ? Math.round(performance.memory.usedJSHeapSize / 1024 / 1024) + 'MB' : 'N/A'}\n`;
            debugInfo += `• Load time: ${Math.round(performance.now())}ms\n\n`;
            
            debugInfo += `💡 TROUBLESHOOTING:\n`;
            debugInfo += `• If shuffle button not working: Check if players >= 2\n`;
            debugInfo += `• If wheel not spinning: Check if players exist\n`;
            debugInfo += `• If settings not opening: Check for JavaScript errors\n`;
            
            // Show debug info
            alert(debugInfo);
            
            // Enhanced console logging
            console.log('=== 🎯 ROULETTE DEBUG INFO ===', {
                gameState: {
                    players: players,
                    isSpinning: isSpinning,
                    spinningTime: spinningTime,
                    playerListVisible: playerListVisible,
                    settingsVisible: settingsVisible
                },
                uiElements: {
                    shuffleBtn: shuffleBtn ? { disabled: shuffleBtn.disabled, classes: shuffleBtn.className } : 'Not found',
                    wheelCenter: wheelCenter ? { disabled: wheelCenter.disabled, classes: wheelCenter.className } : 'Not found',
                    wheel: wheel ? { classes: wheel.className, spinning: wheel.classList.contains('spinning') } : 'Not found'
                },
                textarea: textarea ? {
                    content: textarea.value,
                    length: textarea.value.length,
                    lines: textarea.value.split('\n').length
                } : 'Not found',
                environment: {
                    url: window.location.href,
                    userAgent: navigator.userAgent,
                    screen: `${screen.width}x${screen.height}`,
                    viewport: `${window.innerWidth}x${window.innerHeight}`,
                    timestamp: new Date().toISOString()
                }
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
            
            // Prevent input when player limit is reached
            playersTextarea.addEventListener('keydown', function(e) {
                // Allow backspace, delete, arrow keys, and other navigation keys
                const allowedKeys = [
                    'Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown',
                    'Home', 'End', 'PageUp', 'PageDown', 'Tab', 'Enter'
                ];
                
                if (allowedKeys.includes(e.key)) {
                    return; // Allow these keys
                }
                
                // Check if we're at the limit
                if (shouldRestrictInput()) {
                    e.preventDefault();
                }
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
            
            // Initialize theme
            changeTheme('regular');
            
            // Auto-focus the textarea
            playersTextarea.focus();
            
            // Test shuffle button functionality
            const shuffleBtn = document.getElementById('shuffleBtn');
            console.log('Shuffle button found:', shuffleBtn);
            console.log('Shuffle button disabled:', shuffleBtn.disabled);
            
            // Add event listener for shuffle button
            if (shuffleBtn) {
                shuffleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Shuffle button clicked!');
                    console.log('Button disabled state:', this.disabled);
                    console.log('Players array:', players);
                    
                    // Test if button is actually clickable
                    if (this.disabled) {
                        console.log('Button is disabled, cannot shuffle');
                        return;
                    }
                    
                    shufflePlayers();
                });
                console.log('Shuffle button event listener added');
            } else {
                console.error('Shuffle button not found!');
            }
            
            // Add event listener for duplicate button
            const duplicateBtn = document.getElementById('duplicateBtn');
            console.log('Duplicate button found:', duplicateBtn);
            
            if (duplicateBtn) {
                duplicateBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Duplicate button clicked!');
                    
                    duplicatePlayers();
                });
                console.log('Duplicate button event listener added');
            } else {
                console.error('Duplicate button not found!');
            }
            
            // Initialize with empty player list and show empty wheel
            players = [];
            updatePlayerCountDisplay(0);
            updateRouletteWheel();
            hideGameControls();
            
            // Ensure slow spin is active (it's already in HTML, but make sure it's not removed)
            const wheel = document.getElementById('rouletteWheel');
            if (wheel && !isSpinning) {
                // Set initial rotation if not already set
                if (!wheel.style.getPropertyValue('--current-rotation')) {
                    wheel.style.setProperty('--current-rotation', '0deg');
                }
                wheel.classList.add('slow-spin');
            }
        });
    </script>
</body>
</html>