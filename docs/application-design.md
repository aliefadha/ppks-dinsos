# PPKS Dinsos - Application Design Document

## Overview

This document outlines the design of a Laravel-based assistance management system for Dinas Sosial (Social Services) to manage "bantuan" (assistance programs) and "penerima" (recipients).

## System Architecture

-   **Backend**: Laravel 12.x
-   **Database**: MySQL
-   **Frontend**: SB Admin Theme with Blade templates
-   **Authentication**: Laravel's built-in authentication (admin only)

## Database Design

### Tables

#### 1. `bantuan` (Assistance Programs)

| Column       | Type                                | Description                     |
| ------------ | ----------------------------------- | ------------------------------- |
| id           | bigint, primary key, auto increment | Unique identifier               |
| nama_bantuan | varchar(255)                        | Name of assistance program      |
| deskripsi    | text                                | Description of the assistance   |
| tanggal      | date                                | Date of assistance distribution |
| created_at   | timestamp                           | Record creation time            |
| updated_at   | timestamp                           | Record last update time         |

#### 2. `penerima` (Recipients)

| Column        | Type                                | Description                                |
| ------------- | ----------------------------------- | ------------------------------------------ |
| id            | bigint, primary key, auto increment | Unique identifier                          |
| bantuan_id    | bigint, foreign key                 | Reference to bantuan table (legacy)        |
| nama          | varchar(255)                        | Full name of recipient                     |
| nik           | varchar(16)                         | Indonesian ID number (16 digits)           |
| alamat        | text                                | Full address                               |
| kelurahan     | varchar(255)                        | Village/sub-district                       |
| kecamatan     | varchar(255)                        | District                                   |
| jenis         | varchar(50)                         | Type (disabilitas, lansia, pengemis, etc.) |
| jenis_kelamin | enum('L','P')                       | Gender (L=Laki-laki, P=Perempuan)          |
| created_at    | timestamp                           | Record creation time                       |
| updated_at    | timestamp                           | Record last update time                    |

#### 3. `bantuan_penerima` (Pivot Table)

| Column            | Type                                | Description                            |
| ----------------- | ----------------------------------- | -------------------------------------- |
| id                | bigint, primary key, auto increment | Unique identifier                      |
| bantuan_id        | bigint, foreign key                 | Reference to bantuan table             |
| penerima_id       | bigint, foreign key                 | Reference to penerima table            |
| tanggal_diberikan | date                                | Date assistance was given to recipient |
| created_at        | timestamp                           | Record creation time                   |
| updated_at        | timestamp                           | Record last update time                |

### Relationships

#### Legacy One-to-Many Relationship (Deprecated but Functional)

-   One `bantuan` can have many `penerima` (One-to-Many)
-   Each `penerima` belongs to one `bantuan`

#### New Many-to-Many Relationship (Recommended)

-   One `bantuan` can have many `penerima` (Many-to-Many)
-   One `penerima` can receive many `bantuan` (Many-to-Many)
-   Pivot table `bantuan_penerima` manages the relationships

## Application Structure

### Routes (Indonesian Naming)

-   `/` - Dashboard
-   `/bantuan` - List all assistance programs
-   `/bantuan/create` - Create new assistance program
-   `/bantuan/{id}` - Show assistance program details
-   `/bantuan/{id}/edit` - Edit assistance program
-   `/bantuan/{id}/penerima` - List recipients for specific assistance (legacy)
-   `/bantuan/{id}/penerima/create` - Add recipients (bulk or single) (legacy)
-   `/bantuan/{id}/add-penerimas` - Show form to add recipients (many-to-many)
-   `/bantuan/{id}/store-penerimas` - Store multiple recipients (many-to-many)
-   `/bantuan/{bantuan}/penerima/{penerima}/attach` - Attach recipient to bantuan
-   `/bantuan/{bantuan}/penerima/{penerima}/detach` - Detach recipient from bantuan
-   `/penerima` - List all recipients
-   `/penerima/{id}` - Show recipient details
-   `/penerima/{id}/edit` - Edit recipient details
-   `/penerima/{penerima}/add-bantuans` - Show form to add assistance programs (many-to-many)
-   `/penerima/{penerima}/bantuan/{bantuan}/attach` - Attach assistance to recipient
-   `/penerima/{penerima}/bantuan/{bantuan}/detach` - Detach assistance from recipient

### Controllers

-   `BantuanController` - Handle all bantuan CRUD operations
-   `PenerimaController` - Handle all penerima CRUD operations
-   `DashboardController` - Handle dashboard display

### Models

-   `Bantuan` - Eloquent model for bantuan table
-   `Penerima` - Eloquent model for penerima table
-   `BantuanPenerima` - Eloquent model for bantuan_penerima pivot table

## Features

### 1. Bantuan Management

-   Create, read, update, delete assistance programs
-   View list of all assistance programs
-   View recipients for each assistance program

### 2. Penerima Management

-   Add recipients individually or in bulk
-   Upload recipients via Excel/CSV file
-   View, edit, delete recipient information
-   Filter recipients by assistance program

### 3. Bulk Recipient Addition

-   Manual form with dynamic rows
-   File upload (Excel/CSV) with validation
-   Preview imported data before saving

### 4. Dashboard

-   Total number of assistance programs
-   Total number of recipients
-   Recent assistance programs
-   Recent recipient additions

## UI/UX Design

### Theme

-   SB Admin 2 (Bootstrap-based)
-   Indonesian language throughout
-   Responsive design for mobile and desktop

### Color Scheme

-   Primary: #4e73df (Blue)
-   Success: #1cc88a (Green)
-   Info: #36b9cc (Cyan)
-   Warning: #f6c23e (Yellow)
-   Danger: #e74a3b (Red)

### Navigation

-   Sidebar navigation with Indonesian labels
-   Breadcrumb navigation for easy navigation
-   Search functionality for lists

## Validation Rules

### Bantuan

-   nama_bantuan: required, max:255
-   deskripsi: required
-   tanggal: required, date

### Penerima

-   bantuan_id: required, exists:bantuan,id
-   nama: required, max:255
-   nik: required, digits:16, unique:recipients,nik
-   alamat: required
-   kelurahan: required, max:255
-   kecamatan: required, max:255
-   jenis: required, in:disabilitas,lansia,pengemis,anak_terlantar,keluarga_miskin
-   jenis_kelamin: required, in:L,P

## File Upload Specifications

### Supported Formats

-   Excel (.xlsx, .xls)
-   CSV (.csv)

### Required Columns for Import

-   nama
-   nik
-   alamat
-   kelurahan
-   kecamatan
-   jenis
-   jenis_kelamin (L/P)

## Security Considerations

-   Admin authentication required for all pages
-   CSRF protection on all forms
-   Input validation and sanitization
-   SQL injection prevention through Eloquent ORM
-   File upload validation (type, size)

## Performance Considerations

-   Pagination for large lists
-   Database indexing on foreign keys and searchable fields
-   Lazy loading for relationships
-   File upload size limits

## Future Enhancements

-   Export functionality for reports
-   Advanced filtering and search
-   User roles and permissions
-   Audit trail for changes
-   SMS/email notifications to recipients
