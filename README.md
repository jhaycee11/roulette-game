# Roulette Game Web Application

A modern, interactive roulette game built with Laravel, featuring a beautiful animated wheel, player management, and admin dashboard.

## Features

### 🎯 Core Game Features
- **Interactive Roulette Wheel**: Beautiful animated wheel with smooth spinning animation
- **Player Management**: Add multiple players (up to 37) with name validation
- **Random Winner Selection**: Fair random selection with visual feedback
- **Winner Announcement**: Celebratory winner display with confetti animation

### 📊 Admin Dashboard
- **Game Statistics**: Total games, unique winners, average players per game
- **Recent Winners**: View latest game results
- **Data Management**: Clear all winners functionality
- **Secure Access**: Admin login with credentials

### 🏆 Past Winners
- **Winner History**: Complete list of all past winners
- **Search & Filter**: Find winners by name
- **Sorting Options**: Sort by name, winning number, or date
- **Pagination**: Efficient browsing of large winner lists

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

### Admin Access

- **URL**: `/admin`
- **Username**: `admin`
- **Password**: `roulette2024`

### Viewing Past Winners

- **URL**: `/winners`
- **Features**: Search, sort, and paginate through all past winners

## Technical Details

### Backend
- **Framework**: Laravel 10+
- **Database**: MySQL/PostgreSQL/SQLite
- **Authentication**: Session-based admin authentication
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
│   ├── GameController.php      # Main game logic
│   ├── WinnerController.php    # Past winners management
│   └── AdminController.php     # Admin dashboard
├── Models/
│   └── Winner.php              # Winner model
resources/views/
├── home.blade.php              # Home page with player input
├── game.blade.php              # Roulette wheel game page
├── winners.blade.php           # Past winners listing
└── admin/
    ├── login.blade.php         # Admin login page
    └── dashboard.blade.php     # Admin dashboard
```

## API Endpoints

- `GET /` - Home page
- `POST /players` - Store player names
- `GET /game` - Game page
- `POST /spin` - Spin the wheel (AJAX)
- `GET /winners` - Past winners page
- `GET /admin` - Admin dashboard
- `POST /admin/login` - Admin login
- `POST /admin/logout` - Admin logout
- `DELETE /admin/winners/clear` - Clear all winners

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