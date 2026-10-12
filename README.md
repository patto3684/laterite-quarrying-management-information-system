# LQMS - Laterite Quarry Management Information System

This project is a PHP 8 + MySQL MVC web application for quarry management.

## Run locally
1. Start Apache and MySQL from XAMPP.
2. Open http://localhost/lqsmis in your browser.
3. Login with one of the seeded accounts:
   - admin@lqsmis.com / password
   - officer@lqsmis.com / password
   - inspector@lqsmis.com / password
   - finance@lqsmis.com / password

## Quick Start & Deployment

Quick local start

- Install XAMPP (Apache + MySQL).
- Place this project in `htdocs/lqsmis`.
- Start Apache and MySQL control panel.
- Visit: http://localhost/lqsmis

Create admin user (local or CI)

```bash
php scripts/seed_admin.php
# or with env overrides
SEED_ADMIN_EMAIL=admin@lqsmis.com SEED_ADMIN_PASSWORD='ChangeMe123!' php scripts/seed_admin.php
```

Deploy on Render (recommended for full PHP+DB)

- Add repo to Render, choose Docker, point to `Dockerfile`.
- Set environment variables: `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASSWORD`, `DB_NAME`, `APP_URL`.
- Optionally create a managed database and import `setup.sql`.
- Add GitHub secrets: `RENDER_API_KEY`, `RENDER_SERVICE_ID`, and DB secrets for CI seed.

CI

- A GitHub Actions workflow is included at `.github/workflows/deploy-render.yml` to trigger Render and run the admin seed after deploy.

Static assets on Vercel

- If you want a CDN for images/JS/CSS, deploy the `assets/` folder to Vercel as a static site and update `APP_URL` or image paths accordingly.

Support

If you'd like, I can:
- Fill `render.yaml` with your repo details and branch.
- Update `Dockerfile` to add any additional PHP extensions you need.
- Walk through Render dashboard steps with screenshots or exact clicks.

## Structure
- app/controllers: request handling
- app/models: database access
- app/views: HTML templates
- config: configuration and DB connection
- setup.sql: database schema and seed data
