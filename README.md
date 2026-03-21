# Laravel CRUD

This project is a RESTful API built with **Laravel** that provides authentication and user/role management endpoints.  
Authentication is handled using **JWT tokens**, protecting most routes with the `auth:api` middleware.

---

## Requirements

Before running the project, make sure you have the following installed:

- PHP >= 8.x
- Composer
- Laravel CLI (optional but recommended)
- MySQL or another database supported by Laravel (temporary until PostgreSQL Docker setup is implemented)

---

## Installation

Clone the repository:

```bash
git clone https://github.com/your-username/your-repository.git
cd your-repository
```

Install PHP dependencies:

```bash
composer install
```

Copy the environment file:

```bash
cp .env.example .env
```

Run the migrations:

```bash
php artisan migrate
```
