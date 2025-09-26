# Roulette Game Web Application

A modern, interactive roulette game built with Laravel, featuring a beautiful animated wheel and player management.

## Features

### 🎯 Core Game Features
- **Interactive Roulette Wheel**: Beautiful animated wheel with smooth spinning animation
- **Player Management**: Add multiple players (up to 37) with name validation
- **Random Winner Selection**: Fair random selection with visual feedback
- **Custom Winner Feature**: Set a guaranteed winner that wins 100% of the time
- **Winner Announcement**: Celebratory winner display with confetti animation

### 📱 Modern Design
- **Responsive Layout**: Works perfectly on desktop, tablet, and mobile
- **Beautiful UI**: Modern gradient backgrounds and smooth animations
- **Accessibility**: Proper contrast and readable fonts
- **Interactive Elements**: Hover effects and smooth transitions

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd roulette-game
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate
   ```

5. **Start the application**
   ```bash
   php artisan serve
   ```

## Usage

### Playing the Game

1. **Add Players**: Enter player names on the home page (1-37 players)
2. **Start Game**: Click "Play Roulette" to proceed to the game page
3. **Spin Wheel**: Click "Spin the Wheel" to start the animation
4. **View Winner**: See the winner announcement with confetti celebration

### 🎯 Custom Winner Feature

The game includes a powerful custom winner feature that allows you to set a guaranteed winner:

#### **Setting a Custom Winner**

1. **Access Settings**: Click the golden crown button (👑) in the top-left corner of the game
2. **Enter Winner Name**: Type the exact name that should always win
3. **Enable Feature**: Check the "Enable Custom Winner" checkbox
4. **Save Settings**: Click "Save Settings" to activate

#### **How It Works**

- **Guaranteed Win**: If the custom winner name is in the player list, they will win 100% of the time
- **Auto-Clear**: Custom winner is automatically cleared after they win (prevents repeat wins)
- **Random Fallback**: If the custom winner is not in the player list, the game uses normal random selection
- **Real-time Changes**: You can change the winner name anytime without restarting the game
- **No Sessions**: Uses persistent config files, so settings survive server restarts

#### **Managing Custom Winner**

- **Change Winner**: Visit `/custom-winner` to modify the winner name
- **Disable Feature**: Uncheck "Enable Custom Winner" to return to random selection
- **Clear Settings**: Use "Clear Winner" to remove all custom winner settings
- **Status Check**: The settings page shows current winner name and status

#### **Example Usage**

1. Set custom winner to "John"
2. Add players: "Alice", "Bob", "John", "Charlie"
3. Spin the wheel → "John" will always win
4. Change players to: "Alice", "Bob", "Charlie" (no "John")
5. Spin the wheel → Random selection from Alice, Bob, Charlie


## Technical Details

### Backend
- **Framework**: Laravel 10+
- **Database**: MySQL/PostgreSQL/SQLite
- **Validation**: Server-side input validation and sanitization

### Frontend
- **CSS**: Bootstrap 5 with custom animations
- **JavaScript**: Vanilla JS with smooth animations
- **Icons**: Font Awesome 6
- **Responsive**: Mobile-first design approach

### Security Features
- **CSRF Protection**: All forms protected with CSRF tokens
- **Input Validation**: Server-side validation for all inputs
- **XSS Prevention**: Proper output escaping
- **SQL Injection**: Eloquent ORM protection

## Game Rules

- **Maximum Players**: 37 (matches roulette wheel sections 0-36)
- **Name Length**: Maximum 20 characters per player
- **Unique Names**: Each player must have a unique name
- **Random Selection**: Fair random assignment to wheel sections

## File Structure

```
app/
├── Http/Controllers/
│   ├── GameController.php           # Main game logic
│   └── CustomWinnerController.php   # Custom winner management
resources/views/
├── home.blade.php                   # Home page with player input and game
└── custom-winner/
    └── index.blade.php              # Custom winner settings page
config/
└── customwinner.php                 # Custom winner configuration
```

## API Endpoints

- `GET /` - Home page
- `POST /players` - Store player names
- `POST /spin` - Spin the wheel (AJAX)
- `GET /custom-winner` - Custom winner settings page
- `POST /custom-winner` - Update custom winner settings
- `GET /custom-winner/clear` - Clear custom winner settings
- `GET /api/custom-winner` - Get custom winner data (API)
- `POST /api/custom-winner/clear` - Clear custom winner (API)

## Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is open source and available under the [MIT License](LICENSE).