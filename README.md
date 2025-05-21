# 🍽️ Multi-layered, Microservice-Based Food Ordering Platform

This project is a scalable Laravel-based backend system for a food delivery app inspired by YemekSepeti. The platform is designed to handle different roles: customers, vendors (restaurants), couriers, and administrators (admin/superadmin). It follows clean architecture, modular structure, and prepares for microservice separation.

---

## 🚀 What We've Done

### 🔹 1. Layered Architecture & JWT Auth

- Built with Laravel 10.48 and PHP 8.2
- Applied Controller → Service → Repository → DTO → Response layers
- Implemented secure JWT-based login, register, refresh, and logout
- Role-based access (Customer, Vendor, Admin, SuperAdmin)

### 🔹 2. Modular Structure

- Each module is isolated under `app/Modules/`
- Example modules: `Auth`, `User`, `Company`, `Product`, `Category`
- Each module has its own Controller, Service, Repository, Request, etc.

### 🔹 3. Prepared for Microservice Architecture

- `auth`, `user`, and `company` modules were structured to be easily separable as independent services
- HTTP API + JWT communication between services
- Dockerized each service with individual Dockerfile and docker-compose setup

### 🔹 4. Scramble (Swagger) Integration

- Integrated OpenAPI (Scramble) for API documentation
- Configured middleware skipping to allow testing of JWT-protected routes
- `php artisan scramble:export` generates `api.json` automatically

### 🔹 5. Advanced User & Company Logic

- Vendors can apply to register their restaurant/company
- Admins can approve or reject vendor accounts
- Upon company creation, `company_id` is updated in the related user
- Used `is_active` and `status` flags instead of soft deletes

### 🔹 6. Product & Category Module

- Product fields: `name`, `description`, `sku`, `quantity`, `price`, `category_id`, `company_id`
- Supports multiple images and product videos (stored in separate tables)
- Hierarchical category support (main/subcategory)
- Full CRUD functionality with validation and permissions

---

## ⚙️ Technologies Used

| Layer        | Tech                             |
|--------------|----------------------------------|
| Backend      | Laravel 10.48 + PHP 8.2          |
| Authentication | JWT (Tymon JWT Package)       |
| Frontend     | React (Admin Panel)              |
| Mobile       | Flutter (User & Courier App)     |
| Database     | PostgreSQL                       |
| Docs         | Scramble (OpenAPI/Swagger)       |
| DevOps       | Docker + Docker Compose          |
| Architecture | Modular + Microservice Ready     |

---

## 📁 Modules

- `Auth` – JWT-based authentication
- `User` – User profiles and role management
- `Company` – Vendor/company onboarding and approval
- `Product` – Product management
- `Category` – Product categories

---

## ✅ Next Up

- Courier module (delivery app)
- Order flow & real-time updates
- Notification system (push/email)
- Payment integration (iyzico, Stripe)
- Production deployment with Kubernetes

---

## 📦 How to Run (Locally)

```bash
# Clone the repo
git clone https://github.com/evcne/MarketPlaceApi.git

# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Serve
php artisan serve
