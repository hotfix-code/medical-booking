# Medical Booking

Medical appointment management application built with Laravel 12. It provides administration for patients, doctors, specialties, schedules, consulting rooms, appointments and document types.

## Stack

- **PHP:** `^8.2` (the Sail container uses PHP 8.4).
- **Framework:** Laravel 12.
- **Database:** PostgreSQL (PostgreSQL 17 in `docker-compose.yml`).
- **Authentication:** Laravel Breeze with session-based web authentication.
- **Authorization:** Spatie Laravel Permission and Laravel Policies.
- **Frontend:** Blade, Bootstrap-based UI, Tailwind utilities, Alpine.js and TypeScript.
- **Data tables:** Yajra Laravel DataTables.
- **Assets:** Vite, Axios, Flatpickr, Luxon, Day.js, Select2 and SweetAlert2.
- **Testing and formatting:** Pest and Laravel Pint.
- **Runtime:** Docker and Laravel Sail for local development.

## Requirements

For Docker development:

- Docker and Docker Compose.
- Git.

For a host installation:

- PHP 8.2 or newer with `pdo_pgsql`, `redis`, `gd`, `zip`, `xml` and `mbstring`.
- PostgreSQL.
- Composer.
- Node.js and npm.

## Installation with Sail

Clone the repository and enter the project directory:

```bash
git clone <repository-url> medical-booking
cd medical-booking
```

Copy the environment file and configure the database values if necessary:

```bash
cp .env.example .env
```

Install PHP dependencies, start the containers and generate the application key:

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs

./vendor/bin/sail up -d
sail php artisan key:generate
```

Install frontend dependencies and build the assets:

```bash
sail npm install
sail npm run build
```

Initialize a local database with development data:

```bash
sail php artisan migrate:fresh --seed
```

The default development environment creates test users, roles, permissions and document types. The database seeder chooses the appropriate dataset from `APP_ENV`.

## Installation without Docker

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configure PostgreSQL in `.env`, then initialize the database and assets:

```bash
php artisan migrate:fresh --seed
npm install
npm run build
php artisan serve
```

## Optional local changelog

The changelog is disabled by default and is only available in the local environment. To enable it, set the following values in `.env`:

```env
APP_ENV=local
APP_CHANGELOG_ENABLED=true
```

Its migrations live in a separate directory and must be run explicitly:

```bash
sail php artisan migrate --path=database/migrations/changelog
sail php artisan db:seed --class=ChangelogSeeder
```

The Changelog page reads entries from `changelog_entries` and their localized descriptions from `changelog_entry_translations`. English (`en`) and Spanish (`es`) entries are provided by `ChangelogSeeder`.

If the database is reset with `migrate:fresh`, run the optional migration and seeder again because Laravel does not scan the changelog migration subdirectory automatically.

## Roles and permissions

There are **three default Spatie roles**. Their names are locked (they cannot be renamed or deleted):

| Role | Main access |
|---|---|
| `super-admin` | Full access and permission bypass. |
| `doctor` | Patients, schedules, consulting rooms and appointments assigned to the doctor. |
| `patient` | Doctors, specialties and the patient's own appointments. |

**Admin** is not a Spatie role. It is the class of desk users: anyone in `users` whose role is **not** `doctor` or `patient`. That includes `super-admin` (an admin with every permission) and extra roles created in the UI (for example `tester` in local). The Users module, staff profile form and admin dashboard metrics apply to this class.

The permission catalog and default doctor/patient assignments are defined in [database/data/Permissions.php](database/data/Permissions.php). Policies enforce authorization at the application layer.

## Project structure

```text
app/Http/Controllers   HTTP controllers
app/Http/Requests      Form request validation
app/Models             Eloquent models
app/Policies           Authorization policies
app/Services           Domain services
database/data          Permission and locale definitions
database/migrations    Application migrations
database/seeders       Development and production seeders
resources/views        Blade views and components
resources/js            Frontend scripts
routes                 Web and module routes
```

The application uses Eloquent as its data layer. Related business operations belong in services; controllers should remain thin and delegate validation, authorization and domain work to the appropriate classes.

## Development commands

Start the local application services:

```bash
sail up -d
sail npm run dev
```

Run tests and formatting:

```bash
sail php artisan test
sail pint
```

Useful Laravel commands:

```bash
sail php artisan migrate
sail php artisan migrate:status
sail php artisan db:seed
sail php artisan tinker
sail php artisan optimize:clear
```

For a clean development database:

```bash
sail php artisan migrate:fresh --seed
```

If the optional Changelog is enabled, run its migration and seeder after the reset as described above.

## Production notes

Use a production `.env` with `APP_ENV=production`, `APP_DEBUG=false`, a secure `APP_KEY` and production database credentials. Keep `APP_CHANGELOG_ENABLED=false` in production.

Before deployment:

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

Do not run the development seeder in production. Use the production deployment process and its approved seed data instead.

## References

- [Laravel 12 documentation](https://laravel.com/docs/12.x)
- [Laravel Sail documentation](https://laravel.com/docs/12.x/sail)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v6)
- [Yajra Laravel DataTables](https://yajrabox.com/docs/laravel-datatables)
- [Pest documentation](https://pestphp.com/)
- [TypeScript documentation](https://www.typescriptlang.org/docs/)

## Documentation maintenance

Update this README when installation steps, supported versions, environment variables or operational commands change. Feature-specific notes should live under `docs/notes/`.
