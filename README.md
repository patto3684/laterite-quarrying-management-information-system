# LQMS - Laterite Quarry Management Information System

This project is a PHP 8 + MySQL MVC web application for quarry management.

## Run locally
1. Install XAMPP and start Apache + MySQL.
2. Place this project in `htdocs/lqsmis`.
3. Open http://localhost/lqsmis in your browser.
4. Login with one of the seeded accounts:
   - admin@lqsmis.com / password
   - officer@lqsmis.com / password
   - inspector@lqsmis.com / password
   - finance@lqsmis.com / password

## Create an admin user

```bash
php scripts/seed_admin.php
# or with env overrides
SEED_ADMIN_EMAIL=admin@lqsmis.com SEED_ADMIN_PASSWORD='ChangeMe123!' php scripts/seed_admin.php
```

## Project structure
- app/controllers: request handling
- app/models: database access
- app/views: HTML templates
- config: configuration and DB connection
- setup.sql: database schema and seed data
