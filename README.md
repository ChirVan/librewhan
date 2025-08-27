<p align="center"><strong>Librewhan Cafe SMS & Inventory (Laravel)</strong></p>
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="320" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Framework CI"></a>
<a href="#"><img src="https://img.shields.io/badge/php-8.2%2B-blue" alt="PHP Version"></a>
<a href="#branching"><img src="https://img.shields.io/badge/branching-gitflow%20lite-brightgreen" alt="Branching"></a>
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-lightgrey" alt="License"></a>
</p>

---

## Project Overview

Librewhan is a sales management system (SMS) and basic inventory tracking system focused on beverage (coffee) ordering with size-based pricing, product customizations (milk, toppings, etc.), and order management dashboards. It is built with Laravel + Blade and uses dynamic JavaScript for real-time cart, payment mode, and change calculation logic.

### Core Features (current)
* Order entry UI with cart, size pricing, customizations
* Payment mode selection (Cash / GCash / Maya) and change computation
* Foundational schema (products, sizes, customizations, orders, inventory, activity logs)
* Authentication (Laravel starter auth scaffolding)

### Planned / Next
* Persist orders & items (controllers + migrations wiring)
* Activity logging and inventory decrement per sale
* Reporting / sales charts
* Optional role-based authorization

---

## Quick Start

```bash
cp .env.example .env
php artisan key:generate
composer install
npm install
npm run build   # or: npm run dev
php artisan migrate --seed
php artisan serve
```

Visit: http://127.0.0.1:8000

---

## Branching Model <a id="branching"></a>

We use a lightweight Git Flow style:

* `main` – Always deployable / production-ready
* `develop` – Integration branch for completed features
* `feature/<short-topic>` – New work (e.g. `feature/size-based-pricing`)
* `fix/<issue>` – Bug fixes
* `hotfix/<critical>` – Emergency patch off `main`
* `release/vX.Y.Z` (optional) – Stabilization before tagging

Merge Rules:
1. Feature branches -> Pull Request -> merge into `develop`
2. Periodically merge `develop` -> `main` and tag: `vX.Y.Z`
3. Hotfix: branch from `main`, PR back into both `main` and `develop`

Conventional Commit prefixes recommended: `feat:`, `fix:`, `refactor:`, `docs:`, `chore:`, `test:`.

---

## Collaboration Workflow
1. Sync: `git pull origin develop`
2. Branch: `git checkout -b feature/<topic>`
3. Code + commit (`feat: add payment mode selector`)
4. Rebase (optional): `git fetch origin && git rebase origin/develop`
5. Push: `git push -u origin feature/<topic>`
6. Open PR -> review -> squash or merge

Quality Gates (CI will run):
* PHPStan / Pint (optional future)
* Pest / PHPUnit tests
* Build (npm) if front-end assets needed

---

## Environment Notes
Key .env entries for local dev:

```
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
SESSION_DOMAIN=null
SESSION_SAME_SITE=lax
```

If using a mobile preview / embedded browser that loses cookies, consider: `SESSION_SAME_SITE=none` and (if HTTPS) `SESSION_SECURE_COOKIE=true`.

---

## Testing

Run tests:
```bash
php artisan test
# or
./vendor/bin/pest
```

Use in-memory SQLite for speed by setting in `.env.testing`:
```
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

---

## Deployment (outline)
1. Push tested changes to `main`
2. Tag: `git tag v0.1.0 && git push --tags`
3. Run migrations on server: `php artisan migrate --force`
4. Build assets: `npm ci && npm run build`
5. Cache configs: `php artisan config:cache route:cache view:cache`

---

## Contributing
See `docs/BRANCHING.md` (created in this repo) for deeper details.

---

## Original Laravel README

<details>
<summary>Show framework information</summary>

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
