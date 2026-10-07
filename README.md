# Medical Booking

Medical appointment management application built with Laravel 12. It provides administration for patients, doctors, specialties, schedules, consulting rooms, appointments and document types.

## Stack

- **PHP:** `^8.2` (the Sail container uses PHP 8.4).
- **Framework:** Laravel 12.
- **Database:** PostgreSQL (PostgreSQL 17 in `docker-compose.yml`).
- **Authentication:** Laravel Breeze with session-based web authentication.
- **Authorization:** Spatie Laravel Permission and Laravel Policies.
- **Frontend:** Blade, Bootstrap-based UI and TypeScript.
- **Data tables:** Yajra Laravel DataTables.
- **Assets:** Vite, Axios, Flatpickr, Luxon, Day.js, Select2 and SweetAlert2.
- **Testing and formatting:** Pest and Laravel Pint.
- **Runtime:** Docker and Laravel Sail for local development.

## Installation with Docker Compose and Laravel Sail

### Requirements

The project runs locally with Docker Compose and Laravel Sail. Host PHP, Composer, Node.js, npm and PostgreSQL are not required.

| Requirement    | Details                                                                        |
|----------------|--------------------------------------------------------------------------------|
| Docker         | Docker Desktop or Docker Engine must be installed and running.                 |
| Docker Compose | Compose v2, available as `docker compose`; it is included with Docker Desktop. |

Git is needed to clone the repository.

Clone the repository and enter its directory:

```bash
git clone <repository-url> medical-booking
cd medical-booking
```

Create the environment file. The defaults in `.env.example` are configured for the Docker Compose services:

```bash
cp .env.example .env
```

Install the PHP dependencies with the PHP 8.4 Composer image. This step creates `vendor/`, including the Sail executable:

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

### Optional: configure the `sail` command on Linux

To use `sail` instead of typing `./vendor/bin/sail`, add the following alias to `~/.bashrc` when using Bash or `~/.zshrc` when using Zsh. This follows [Laravel Sail's shell alias guidance](https://laravel.com/docs/12.x/sail#configuring-a-shell-alias):

```bash
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
```

Apply the change in the current terminal with `source ~/.bashrc` or `source ~/.zshrc`, depending on your shell. You can also close and reopen the terminal. The commands below use `sail`; without the alias, replace `sail` with `./vendor/bin/sail`.

Sail supports both `sail artisan <command>` and `sail php artisan <command>` for Laravel Artisan commands. Both run Artisan with the PHP interpreter in the application container; this guide uses the shorter `sail artisan` form.

Start the application and PostgreSQL containers, then generate the application key:

```bash
sail up -d
sail artisan key:generate
```

Install the frontend dependencies declared in `package.json`:

```bash
sail npm install
```

Create the optimized frontend bundle for production:

```bash
sail npm run build
```

This compiles the assets configured in Vite and writes them to `public/build`, minifying JavaScript and any CSS imported by those entries.

For frontend development, start Vite's development server:

```bash
sail npm run dev
```

Keep this process running while editing frontend files. Vite serves the assets and applies live updates in the browser.

Create the database tables and seed the development data:

```bash
sail artisan migrate --seed
```

The `--seed` option runs the default `DatabaseSeeder` after the migrations. It is equivalent to running `sail artisan migrate` followed by `sail artisan db:seed`; use `sail artisan migrate` alone when you do not want to seed the database.

Open the application at [http://localhost](http://localhost). If you change `APP_PORT` in `.env`, use that port instead.

The default local seeders create development data, including users, roles, permissions and document types. The database seeder selects the appropriate dataset from `APP_ENV`.

### Seeded super-admin login

The local and production seeders currently create this super-admin account. After running `sail artisan migrate --seed`, sign in with:

| Email                | Password   |
|----------------------|------------|
| `admin@medibook.org` | `password` |

For production, treat these as temporary bootstrap credentials only. Keep the site private, sign in, and change the password from the profile page before making the application publicly accessible. The application does not force this change.

## Optional local changelog

The changelog is disabled by default and is only available in the local environment. To enable it, set the following values in `.env`:

```env
APP_ENV=local
APP_CHANGELOG_ENABLED=true
```

Its migrations live in a separate directory and must be run explicitly:

```bash
sail artisan migrate --path=database/migrations/changelog
sail artisan db:seed --class=ChangelogSeeder
```

The Changelog page reads entries from `changelog_entries` and their localized descriptions from `changelog_entry_translations`. English (`en`) and Spanish (`es`) entries are provided by `ChangelogSeeder`.

If the database is reset with `migrate:fresh`, run the optional migration and seeder again because Laravel does not scan the changelog migration subdirectory automatically.

## Roles and permissions

There are **three default Spatie roles**. Their names are locked (they cannot be renamed or deleted):

| Role          | Main access                                                                    |
|---------------|--------------------------------------------------------------------------------|
| `super-admin` | Full access and permission bypass.                                             |
| `doctor`      | Patients, schedules, consulting rooms and appointments assigned to the doctor. |
| `patient`     | Doctors, specialties and the patient's own appointments.                       |

**Admin** is not a Spatie role. It is the class of desk users: anyone in `users` whose role is **not** `doctor` or `patient`. That includes `super-admin` (an admin with every permission) and extra roles created in the UI (for example `tester` in local). The Users module, staff profile form and admin dashboard metrics apply to this class.

The permission catalog and default doctor/patient assignments are defined in [database/data/Permissions.php](database/data/Permissions.php). Policies enforce authorization at the application layer.

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

Do not run the development seeder in production. The `ProductionSeeder` creates the initial super-admin using the credentials above. Keep the application inaccessible to the public until that account's password has been changed from its profile page. The production process should also ensure the account uses a unique, strong password before public access.

For a Dockerized production deployment, consider a dedicated FrankenPHP image rather than using the Sail configuration intended for local development. FrankenPHP documents running Laravel in its Docker image, and Laravel Octane also supports FrankenPHP. Use a separate production Dockerfile and Compose configuration for that deployment; see the [Laravel Octane guide](https://laravel.com/docs/12.x/octane#frankenphp-via-docker) and [FrankenPHP production guide](https://frankenphp.dev/docs/production/).

## References

- [Laravel 12 documentation](https://laravel.com/docs/12.x)
- [Laravel Sail documentation](https://laravel.com/docs/12.x/sail)
- [Laravel Octane with FrankenPHP](https://laravel.com/docs/12.x/octane#frankenphp-via-docker)
- [FrankenPHP production deployment](https://frankenphp.dev/docs/production/)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v6)
- [Yajra Laravel DataTables](https://yajrabox.com/docs/laravel-datatables)
- [Pest documentation](https://pestphp.com/)
- [TypeScript documentation](https://www.typescriptlang.org/docs/)
