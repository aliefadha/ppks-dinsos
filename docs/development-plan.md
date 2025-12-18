# PPKS Dinsos - Development Plan

## Overview

This document outlines the step-by-step development plan for building the PPKS Dinsos assistance management system using Laravel, MySQL, and SB Admin theme.

## Development Phases

### Phase 0: Simple Authentication Setup (Day 0.5)

#### 0.1 Basic Authentication Implementation

-   [x] Create `LoginController` for handling login/logout
-   [x] Create simple login view with email/password form
-   [x] Create `UserSeeder` for admin user credentials
-   [x] Update routes with login/logout and protected routes
-   [x] Add authentication middleware to protect application routes
-   [x] Create basic layout with logout functionality
-   [x] Test authentication flow

#### 0.2 Authentication Configuration Details

**LoginController Structure:**

```php
// Methods to implement:
- login() // Show login form
- authenticate() // Handle login submission
- logout() // Handle logout
```

**UserSeeder Configuration:**

```php
// Default admin credentials:
- Email: admin@ppks.local
- Password: password123
```

**Routes Structure:**

```php
// Public routes
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/authenticate', [LoginController::class, 'authenticate']);

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    // All other application routes...
});
```

### Phase 1: Foundation Setup (Day 1-2) - COMPLETED

#### 1.1 Environment Configuration

-   [x] Configure `.env` file with database settings
-   [x] Update application name to "PPKS Dinsos"
-   [x] Set timezone to Asia/Jakarta
-   [x] Configure locale to Indonesian (id)

#### 1.2 Database Setup

-   [x] Create database migrations for `bantuan` table
-   [x] Create database migrations for `penerima` table
-   [x] Run migrations to create tables
-   [x] Create seeders for initial data (optional)

#### 1.3 Models and Relationships

-   [x] Create `Bantuan` model with fillable fields
-   [x] Create `Penerima` model with fillable fields
-   [x] Define one-to-many relationship between Bantuan and Penerima
-   [x] Add validation rules to models

### Phase 2: Core Functionality (Day 3-4)

#### 2.1 Controllers

-   [x] Create `BantuanController` with CRUD methods
-   [x] Create `PenerimaController` with CRUD methods
-   [x] Create `DashboardController` for statistics
-   [x] Implement resource routing

#### 2.2 Routes Configuration

-   [x] Set up routes with Indonesian naming conventions
-   [x] Implement resource routes for bantuan
-   [x] Implement resource routes for penerima
-   [x] Add routes for bulk operations
-   [x] Add authentication middleware

#### 2.3 Basic Views

-   [x] Create main layout with SB Admin theme
-   [x] Create dashboard view with statistics
-   [x] Create bantuan index view
-   [x] Create bantuan create/edit forms
-   [x] Create bantuan show view
-   [x] Create penerima index view
-   [x] Create penerima create/edit forms
-   [x] Create penerima show view

### Phase 3: Advanced Features (Day 5-6)

#### 3.1 Bulk Recipient Addition

-   [x] Create form for manual bulk addition
-   [x] Implement dynamic row addition with JavaScript
-   [x] Add validation for bulk form submission
-   [ ] Handle import errors and validation

#### 3.2 Enhanced UI/UX

-   [ ] Implement search and filtering
-   [ ] Add pagination for large datasets
-   [ ] Implement confirmation dialogs for delete actions
-   [ ] Add success/error notifications
-   [ ] Implement loading states

#### 3.3 Validation and Error Handling

-   [x] Add NIK format validation (16 digits)
-   [x] Add custom error messages in Indonesian
-   [x] Implement form validation with client-side feedback
-   [x] Handle file upload errors gracefully (not applicable - no file upload feature yet)

### Phase 4: Testing and Documentation (Day 7)

#### 4.1 Testing

-   [ ] Test all CRUD operations
-   [ ] Test bulk addition functionality
-   [ ] Test file import with various formats
-   [ ] Test validation rules
-   [ ] Perform responsive design testing

#### 4.2 Documentation

-   [ ] Complete API documentation
-   [ ] Create user manual for administrators
-   [ ] Document deployment process
-   [ ] Add code comments where necessary

## Technical Implementation Details

### Database Migrations Structure

#### Bantuan Migration

```php
Schema::create('bantuan', function (Blueprint $table) {
    $table->id();
    $table->string('nama_bantuan', 255);
    $table->text('deskripsi');
    $table->date('tanggal');
    $table->timestamps();
});
```

#### Penerima Migration

```php
Schema::create('penerima', function (Blueprint $table) {
    $table->id();
    $table->foreignId('bantuan_id')->constrained('bantuan');
    $table->string('nama', 255);
    $table->string('nik', 16)->unique();
    $table->text('alamat');
    $table->string('kelurahan', 255);
    $table->string('kecamatan', 255);
    $table->string('jenis', 50);
    $table->enum('jenis_kelamin', ['L', 'P']);
    $table->timestamps();
});
```

### Routes Structure

```php
// Public routes (no authentication required)
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('authenticate');

// Protected routes (authentication required)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Authentication
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Bantuan Resources
    Route::resource('bantuan', BantuanController::class);
    Route::get('bantuan/{id}/penerima', [BantuanController::class, 'penerima'])->name('bantuan.penerima');
    Route::get('bantuan/{id}/penerima/create', [BantuanController::class, 'createPenerima'])->name('bantuan.createPenerima');
    Route::post('bantuan/{id}/penerima/store', [BantuanController::class, 'storePenerima'])->name('bantuan.storePenerima');

    // Penerima Resources
    Route::resource('penerima', PenerimaController::class);
    Route::post('penerima/import', [PenerimaController::class, 'import'])->name('penerima.import');
});
```

### File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   └── LoginController.php
│   │   ├── BantuanController.php
│   │   ├── PenerimaController.php
│   │   └── DashboardController.php
├── Models/
│   ├── User.php
│   ├── Bantuan.php
│   └── Penerima.php
database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_bantuan_table.php
│   └── xxxx_xx_xx_xxxxxx_create_penerima_table.php
├── seeders/
│   └── UserSeeder.php
resources/
├── views/
│   ├── layouts/
│   │   ├── app.blade.php
│   │   ├── sidebar.blade.php
│   │   └── navbar.blade.php
│   ├── auth/
│   │   └── login.blade.php
│   ├── bantuan/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   ├── show.blade.php
│   │   └── penerima/
│   │       ├── index.blade.php
│   │       ├── create.blade.php
│   │       └── import.blade.php
│   ├── penerima/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   └── dashboard/
│       └── index.blade.php
```

## Dependencies to Install

### Laravel Packages

```bash
# Excel/CSV handling
composer require maatwebsite/excel

# Form handling
composer require laravelcollective/html

# Additional validation rules (optional)
composer require.propaganistas/laravel-phone
```

### NPM Packages

```bash
# SB Admin 2 theme files
npm install @fortawesome/fontawesome-free
npm install chart.js
npm install datatables.net
npm install datatables.net-bs4
```

## Deployment Considerations

### Environment Variables

```
APP_NAME="PPKS Dinsos"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ppks_dinsos
DB_USERNAME=username
DB_PASSWORD=password
```

### Server Requirements

-   PHP >= 8.2
-   MySQL >= 8.0
-   Composer
-   Node.js & NPM
-   Web server (Apache/Nginx)

## Timeline Summary

-   **Day 1-2**: Foundation setup and database design
-   **Day 3-4**: Core CRUD functionality implementation
-   **Day 5-6**: Advanced features and UI enhancements
-   **Day 7**: Testing, documentation, and deployment preparation

## Notes

-   All UI text should be in Indonesian
-   Follow Laravel coding standards
-   Implement proper error handling
-   Use Indonesian naming conventions for database tables, columns, and routes
-   Ensure responsive design for mobile compatibility
