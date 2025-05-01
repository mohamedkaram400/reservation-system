<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


# Simple Service Reservation System

A lightweight platform built with Laravel to allow users to browse, reserve, and manage services like consultations or coaching sessions. Admins can manage services and monitor reservations through a dashboard.


## Business Requirements Understanding:
The goal is to build a simple, user-friendly platform for booking services like **consultations** or **repairs**. It should make scheduling easy for users and management smooth for admins, with room to grow into features like notifications and analytics.

## Feature Suggestion
**Email Notifications**: Email Notifications: Automatically send emails for reservations, reminders. This keeps users updated and helps reduce missed appointments.

## Important Note
I have prior experience in this domain, as I’ve worked on a clinic system that involved **scheduling appointments** and meetings between doctors and patients. This included handling complex date, time, and **timezone** logic to ensure accurate booking and reminders.

## Features
- **User Authentication**: Register, log in, and log out securely.
- **Service Listing**: Browse services with name, description, price, and availability. Admins can add, edit, or delete services.
- **Reservation Flow**: Logged-in users can reserve services by selecting a date/time. Reservations track user ID, service ID, date/time, and status.
- **Reservation Management** (Bonus): Users can view upcoming/past reservations and cancel them (with a 24-hour cancellation rule).
- **Admin Dashboard & APIs** (Bonus): RESTful APIs for listing services and managing reservations. Admin dashboard to monitor all reservations.
- **Basic Frontend**: Simple Blade templates with Bootstrap for a clean UI.


## Design Decisions
- **Laravel Authentication**: Used Laravel Breeze for quick, secure user authentication.
- **Database Schema**: Normalized tables for users, services, and reservations to prevent redundancy.
- **APIs**: Built RESTful endpoints with Laravel Sanctum for secure access.
- **Edge Cases**: Handled double bookings by checking availability during reservation and enforced a 24-hour cancellation policy.


## Tech Stack
- **Backend**: PHP 8.3, Laravel 12
- **Database**: MySQL
- **APIs**: Laravel API routes with Sanctum for authentication

## Setup Instructions
1. **Clone the Repository**:
   ```
   git clone <repository-url>
   cd service-reservation
   ```
2. **Install Dependencies**:
   ```
   composer install
   ```
3. **Configure Environment**:
   - Copy `.env.example` to `.env`.
   - Set database credentials and `APP_URL`.
   ```
   cp .env.example .env
   ```
4. **Run Migrations**:
   ```
   php artisan migrate
   ```
5. **Seed Database**:
   ```
   php artisan db:seed
   ```
6. **Start the Server**:
   ```
   php artisan serve
   ```
## Endpoint Collection
The full list of API endpoints is included in the project’s main directory for easy reference and testing.