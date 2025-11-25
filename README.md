# Medical Management System - Technical Documentation

## 📋 General Overview

Comprehensive medical management system developed with **Laravel 12** and **PostgreSQL** that enables complete administration of medical appointments, doctors, patients, and hospital resources. The system implements a robust role and permission system that ensures security and granular access control for professional medical environments.

## 🏗️ Technical Architecture

### Technology Stack
- **Framework**: Laravel 12
- **PHP**: 8.4+
- **Database**: PostgreSQL 17+
- **Authentication**: Laravel Sanctum
- **Permissions**: Spatie Laravel Permission
- **Frontend**: Blade Templates + DataTables + TypeScript
- **CSS Framework**: TailwindCSS with Alpine.js
- **Containerization**: Docker + Laravel Sail
- **UUID**: Universal unique identifiers for all entities
- **Testing**: Pest + Laravel Testing Suite
- **Code Style**: Laravel Pint
- **Build Tool**: Vite

### Frontend Technologies
- **TypeScript**: Strong typing for JavaScript
- **Vite**: Fast build tool and dev server
- **Additional Libraries**:
    - SweetAlert2: Beautiful alerts and modals
    - Flatpickr: Date/time picker
    - Luxon: Date manipulation
    - Day.js: Date parsing and formatting
    - Select2: Enhanced select boxes
    - Axios: HTTP client

### Architectural Principles
- **MVC Pattern**: Model-View-Controller
- **Service Layer Pattern**: Business logic encapsulated in services
- **Repository Pattern**: Data access through Eloquent models
- **Policy-based Authorization**: Granular policy-based authorization
- **Single Responsibility Principle**: Each class has a specific responsibility
- **Type Safety**: TypeScript for frontend development

## 👥 Role and Permission System

### Available Roles

#### 🔴 Super Admin
- **Description**: Administrator with complete system access
- **Permissions**: Total access to all functionalities without restrictions
- **Special Feature**: Automatic bypass of all permission validations

#### 🟡 Admin
- **Description**: Administrator with limited permissions for general management
- **Access to**:
    - Complete system user management
    - Document type configuration
    - Role and permission management (except super-admin)
    - System reports and metrics

#### 🟢 Doctor
- **Description**: Medical professional with access to clinical functionalities
- **Specific Permissions**:
    - `patient.view` - View patient information
    - `consulting_room.view` - View available consulting rooms
    - `schedule.view` - View medical schedules
    - `appointment.view` - View medical appointments
    - `appointment.edit` - Modify medical appointments

- **Functionalities**:
    - Personal schedule management
    - Assigned patient visualization
    - Appointment status modification
    - Consulting room information access

#### 🔵 Patient
- **Description**: End user of the medical system
- **Specific Permissions**:
    - `doctor.view` - View doctor information
    - `specialty.view` - View medical specialties
    - `appointment.view` - View their own medical appointments

- **Functionalities**:
    - Doctor and specialty consultation
    - Scheduled appointment visualization
    - Limited access to own information only

### Detailed Permission Matrix

| Module | Action | Super Admin | Admin | Doctor | Patient |
|--------|--------|-------------|-------|---------|---------|
| **System Users** | | | | | |
| Users | Create/Edit/Delete | ✅ | ✅ | ❌ | ❌ |
| Users | View | ✅ | ✅ | ❌ | ❌ |
| **Patient Management** | | | | | |
| Patients | Create/Edit/Delete | ✅ | ✅ | ❌ | ❌ |
| Patients | View | ✅ | ✅ | ✅ | ❌* |
| **Doctor Management** | | | | | |
| Doctors | Create/Edit/Delete | ✅ | ✅ | ❌ | ❌ |
| Doctors | View | ✅ | ✅ | ❌ | ✅ |
| **Specialties** | | | | | |
| Specialties | Create/Edit/Delete | ✅ | ✅ | ❌ | ❌ |
| Specialties | View | ✅ | ✅ | ❌ | ✅ |
| **Medical Schedules** | | | | | |
| Schedules | Create/Edit/Delete | ✅ | ✅ | ❌ | ❌ |
| Schedules | View | ✅ | ✅ | ✅ | ❌ |
| **Consulting Rooms** | | | | | |
| Rooms | Create/Edit/Delete | ✅ | ✅ | ❌ | ❌ |
| Rooms | View | ✅ | ✅ | ✅ | ❌ |
| **Medical Appointments** | | | | | |
| Appointments | Create/Delete | ✅ | ✅ | ❌ | ❌ |
| Appointments | View | ✅ | ✅ | ✅ | ✅* |
| Appointments | Edit | ✅ | ✅ | ✅ | ❌ |
| **Configuration** | | | | | |
| Roles | Complete Management | ✅ | ✅ | ❌ | ❌ |
| Permissions | View/Edit | ✅ | ✅ | ❌ | ❌ |
| Document Types | Complete Management | ✅ | ✅ | ❌ | ❌ |

*\* Own information only*

## 🚀 Role-based Functionalities

### Super Admin
- Complete dashboard with general metrics
- Total user and role management
- System configuration
- Log and audit access
- Data backup and restoration

### Admin
- Dashboard with limited metrics
- Doctor and patient management
- Basic system configuration
- Operational reports
- Medical appointment management

### Doctor
- Personalized medical dashboard
- Assigned patient list
- Personal appointment calendar
- Own schedule management
- Appointment status modification

### Patient
- Simplified personal dashboard
- Scheduled appointment visualization
- Doctor and specialty directory
- Personal profile (read-only)
- Basic appointment history

## 🔧 Configuration and Deployment

### System Requirements
- **PHP**: 8.4+ with required extensions
- **PostgreSQL**: 17+
- **Docker & Docker Compose**: For local development
- **Composer**: For PHP dependency management
- **Node.js**: 20+ (for asset compilation)
- **NPM/Yarn**: For frontend dependencies

### Installation with Docker (Recommended)

#### 1. Repository Cloning
```bash
git clone <repository-url>
cd medical-management-system
```

#### 2. Create Docker Network
Before running Sail, create the Docker network. **By default, the network is named `sail`** (as defined in `docker-compose.yml`). If you want to use the default configuration, create it with:
```bash
docker network create sail
```

**Optional - Custom Network Name:**
If you prefer to use a different network name (e.g., `medical_system`), you need to:
1. Create the network with your desired name:
```bash
docker network create medical_system
```
2. Update the `docker-compose.yml` file to use your custom network name by changing all occurrences of `sail` to your network name in the `networks` sections.

#### 3. Environment Configuration
Copy and configure the environment file:
```bash
cp .env.example .env
```

Configure main variables:
```env
# Application
APP_NAME="Medical Management System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=pgsql
DB_HOST=medical_db
DB_PORT=5432
DB_DATABASE=medical_system
DB_USERNAME=medical_user
DB_PASSWORD=secure_password

# Redis (Sessions & Cache)
REDIS_HOST=medical_redis
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail Configuration
MAIL_MAILER=mailpit
MAIL_HOST=medical_mailpit
MAIL_PORT=1025

# Optional: Customize ports
APP_PORT=8080
FORWARD_DB_PORT=54320
FORWARD_REDIS_PORT=63790
VITE_PORT=5173
```

#### 4. Install Dependencies
Generate the vendor directory:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

#### 5. Start Application
```bash
# Start all services
./vendor/bin/sail up -d

# Recommended alias for convenience
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
```

#### 6. Initial Application Configuration
```bash
# Generate application key
sail php artisan key:generate

# Run migrations
sail php artisan migrate:fresh

# Run seeders (initial data)
sail php artisan db:seed --class=ProductionSeeder

# Install Node.js dependencies
sail npm install

# Compile assets
sail npm run build
```

### Traditional Installation (Without Docker)

#### Prerequisites
- PHP 8.2+ with extensions: pdo_pgsql, redis, gd, zip, xml, mbstring
- PostgreSQL 15+ running
- Composer installed globally
- Node.js 18+ with npm

#### Installation Steps
```bash
# 1. Install PHP dependencies
composer install

# 2. Configure environment
cp .env.example .env
php artisan key:generate

# 3. Configure database in .env
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_PORT=5432
# ...

# 4. Run migrations and seeders
php artisan migrate:fresh --seed

# 5. Install and compile assets
npm install
npm run build

# 6. Start development server
php artisan serve
```

## 🛠️ Development Environment

### Recommended Development Flow

#### 1. New Feature Development
```bash
# 1. Create feature branch
git checkout -b feature/new-medical-functionality

# 2. Write tests first (TDD)
sail php artisan make:test NewMedicalFunctionalityTest

# 3. Run tests (should fail initially)
sail php artisan test --filter=NewMedicalFunctionalityTest

# 4. Implement functionality
# ... develop code ...

# 5. Verify tests pass
sail php artisan test

# 6. Apply code standards
sail php artisan pint

# 7. Run static analysis
sail composer analyse
```

#### 2. Debugging and Monitoring
```bash
# View application logs
sail logs -f medical-app

# Access database
sail psql medical_system

# Run tinker for debugging
sail php artisan tinker

# Clear caches during development
sail php artisan optimize:clear
```

#### 3. Development Database
```bash
# Reset DB with test data
sail php artisan migrate:fresh --seed --class=DevelopmentSeeder

# Create new migrations
sail php artisan make:migration create_medical_records_table

# Create specific seeders
sail php artisan make:seeder MedicalRecordsSeeder

# Check migration status
sail php artisan migrate:status
```

### Productivity Tools

#### Useful Aliases
```bash
# Add to ~/.bashrc or ~/.zshrc
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
alias sa='sail php artisan'
alias st='sail php artisan test'
alias sp='sail php artisan pint'
alias medical-logs='sail logs -f medical-app'
alias medical-db='sail psql medical_system'
```

#### Development Scripts
```bash
# scripts/dev-reset.sh
#!/bin/bash
echo "🔄 Resetting development environment..."
sail down
sail up -d
sail php artisan migrate:fresh --seed --class=DevelopmentSeeder
sail npm run build
echo "✅ Development environment ready!"
```

### Production Configuration

#### Critical Environment Variables
```env
# Security
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_32_CHARACTER_KEY

# Database
DB_CONNECTION=pgsql
DB_HOST=production_host
DB_DATABASE=medical_production
DB_USERNAME=production_user
DB_PASSWORD=secure_production_password

# Cache & Sessions
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Mail
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
```

#### Production Optimizations
```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Compile assets for production
npm run build
```

## 📚 Additional Resources

### Technical Documentation
- [Laravel 12 Documentation](https://laravel.com/docs/12.x)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v6)
- [PostgreSQL Documentation](https://www.postgresql.org/docs/15/)
- [Laravel Sail Documentation](https://laravel.com/docs/12.x/sail)
- [Pest Testing Framework](https://pestphp.com/)
- [TypeScript Documentation](https://www.typescriptlang.org/docs/)

### Recommended Tools
- **IDE**: PhpStorm or VS Code with PHP extensions
- **Database**: TablePlus, pgAdmin, or DBeaver
- **API Testing**: Postman or Insomnia
- **Version Control**: Git with GitKraken or SourceTree
- **Deployment**: Laravel Forge, Vapor, or Docker Swarm

### Quick Reference Commands
```bash
# Application
sail up -d / sail down
sail php artisan serve
sail php artisan queue:work

# Database
sail php artisan migrate
sail php artisan migrate:rollback
sail php artisan db:seed

# Testing
sail php artisan test
sail php artisan test --coverage
sail php artisan pint

# Cache
sail php artisan config:cache
sail php artisan route:cache
sail php artisan optimize:clear

# Development
sail composer install
sail npm install && npm run build
sail php artisan make:controller MedicalController

# Useful development commands
sail composer dev  # Start all development services
sail composer test # Run configuration and tests
```

## 🔮 Roadmap and Future Improvements

### Planned Functionalities

### **Version 1.1 – Q1 2026**
**Planned Features**
- Reports module (PDF and Excel export)
- User interface and design improvements
- Full Spanish translation using Laravel `lang`

**Technical Enhancements**
- Production-ready Dockerization using FrankenPHP and Traefik
- Integration with AWS CodeBuild and CodePipeline for CI/CD deployment
- Optimization of asset loading with Vite and TypeScript

---

### **Version 1.2 – Q2 2026**
**Planned Features**
- Transactional email notifications (appointment confirmation, reminders, cancellations)
- Push and SMS notification system

**Technical Enhancements**
- Redis caching implementation
- Queue system for heavy processes (email delivery, report generation)
- Integration of performance monitoring with Laravel Telescope

---

**Document Version**: 1.0  
**Last Updated**: November 2025  
**Responsible**: Development Team  
**Stack Version**: Laravel 12 + PHP 8.4 + PostgreSQL 17 + TypeScript

> This documentation should be kept updated with each significant system change. To contribute, follow the development guidelines and ensure all tests pass before submitting changes.
