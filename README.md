# CreatorBox – Creator Support Platform

## Project Overview
CreatorBox is a full-stack web application built to connect creative creators with their fans. The platform enables creators to build and manage a community, share exclusive content, and receive monthly support through a virtual coin system. Fans can support their favorite creators, engage with posts, and earn coins via daily logins and interactions.

---

## Features

### For Unauthenticated Visitors
- Browse public posts from creators
- View creator profiles and sponsorship plans
- Register as a new fan

### For Fans (Logged In)
- Dashboard with followed creators and their posts
- Like posts (non-reversible) and comment based on creator settings
- Support creators by subscribing to monthly sponsorship plans
- Earn coins through daily logins and liking posts
- Manage personal profile and information

### For Creators (Logged In)
- Create and manage posts (blog, image, file) with advanced visibility settings
- Create and manage monthly sponsorship plans
- View supporter list and community engagement metrics
- Track coin income from plans and post likes

---

## Setup & Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL
- Node.js & npm

### Steps
1. **Clone the repository:**
   ```bash
   git clone https://github.com/wxhwys314/creator-box.git
   cd CreatorBox
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install frontend dependencies:**
   ```bash
   npm install
   ```

4. **Copy environment file and configure:**
   ```bash
   cp .env.example .env
   ```
   Update `.env` with your database credentials and other environment settings.

5. **Run database migrations:**
   ```bash
   php artisan migrate
   ```

6. **Create storage symlink:**
   ```bash
   php artisan storage:link
   ```

7. **(Optional) Seed the database with sample data:**
   ```bash
   php artisan db:seed
   ```

8. **Start the Laravel development server:**
   ```bash
   php artisan serve
   ```

9. **Compile frontend assets:**
   ```bash
   npm run dev
   ```

10. **Visit the application:**
   ```
   http://localhost:8000
   ```

## Testing
Manual testing has been performed on core functionalities:
- User authentication and role-based access
- Post creation, visibility settings, and interactions
- Sponsorship plan management and coin transactions
- Daily login rewards and like-based coin earnings

A test report is available upon request.