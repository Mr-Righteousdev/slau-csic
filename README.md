# Cybersecurity & Innovations Club Website

This repository contains the source code for the **SLAU Cybersecurity & Innovations Club** website.
It is built on top of Laravel, Tailwind CSS, Alpine.js (and optionally Livewire later) and is
meant to be easy for contributors to clone, run locally, and improve – especially on the
frontend.

---

## 1. Cloning the project from GitHub

> Replace `YOUR_GITHUB_USERNAME` with the actual GitHub username or org that hosts this repo.

```bash
git clone https://github.com/YOUR_GITHUB_USERNAME/cyber-web-app.git
cd cyber-web-app
```

If you already have a local clone, just pull the latest changes:

```bash
git pull origin main
```

---

## 2. Minimal backend setup (Laravel)

You only need this section if you want to run the full Laravel app locally.

Requirements (recommended):

- PHP 8.2+
- Composer
- Node.js 18+ and npm

### 2.1 Install dependencies

```bash
composer install
npm install
```

### 2.2 Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

If you are just working on the frontend views and styles, you can usually
leave the default SQLite or simple local config in `.env` as-is.

### 2.3 Run the app

In one terminal:

```bash
php artisan serve
```

In another terminal:

```bash
npm run dev
```

Visit the app at `http://localhost:8000`.

---

## 3. Frontend-only contribution workflow (easier path)

If you mostly want to work on the **public website UI** (no heavy Laravel/PHP work),
this is the recommended flow.

### 3.1 Where the frontend lives

- Public site layout: `resources/views/layouts/frontend.blade.php`
- Public pages: `resources/views/frontend/`
  - `home.blade.php` – landing page
  - `about.blade.php` – about the club
  - `events.blade.php` – events overview
  - `team.blade.php` – organizing team
  - `contact.blade.php` – contact form + info
- Shared frontend components: `resources/views/frontend/components/`
  - `navbar.blade.php` – top navigation
  - `footer.blade.php` – footer
- Tailwind entry CSS: `resources/css/app.css`
- JS / Alpine init & dynamic imports: `resources/js/app.js`

You can do a lot of frontend work by editing only the Blade templates and Tailwind
classes, without touching controllers, models, or database code.

### 3.2 Steps for frontend contributors

1. **Fork** this repository on GitHub.
2. **Clone** your fork and install Node dependencies:
   ```bash
   npm install
   ```
3. **Run the frontend dev server** (Vite) together with the Laravel server:
   ```bash
   php artisan serve
   npm run dev
   ```
4. **Edit frontend files** under `resources/views/frontend` and `resources/views/layouts/frontend.blade.php`.
5. Use Tailwind utility classes directly in Blade for styling.
6. When ready, **commit** your changes, **push** a feature branch to your fork,
   and open a **Pull Request (PR)** against the main repo.

If you are unsure whether a change belongs in frontend-only or needs Laravel changes,
open an issue or PR draft and ask.

---

## 4. Technology & documentation links

You do not need to be an expert in all of these, but these docs are useful
references while contributing.

### Laravel (backend framework)
- Official docs: https://laravel.com/docs
- Blade templating: https://laravel.com/docs/blade

### Tailwind CSS (styling)
- Official docs: https://tailwindcss.com/docs
- Utility reference: https://tailwindcss.com/docs/utility-first

### Alpine.js (lightweight JavaScript)
- Official docs: https://alpinejs.dev

### Livewire (optional, for interactive components)
The project does not require Livewire for basic frontend work, but we may
use it for more interactive pieces.

- Official docs: https://livewire.laravel.com

Before adding Livewire-based components, please discuss it in an issue or PR
so we keep the stack consistent.

---

## 5. How to contribute

We welcome contributions from people at all levels – especially students who
are learning Laravel, Tailwind, and general web development.

### 5.1 General contribution steps

1. **Open an issue** (optional but recommended) describing what you want to change or add.
2. **Fork** the repo and create a branch for your work:
   ```bash
   git checkout -b feature/short-description
   ```
3. Make your changes (prefer small, focused commits).
4. Run the dev servers and manually check the pages you touched.
5. Push your branch and open a **Pull Request** with a clear description
   (what you changed, why, and any screenshots for UI changes).

### 5.2 Frontend best practices

- Prefer Tailwind utility classes instead of custom CSS when possible.
- Keep Blade templates readable; extract repeated chunks into Blade components.
- Use semantic HTML (`<section>`, `<nav>`, `<header>`, etc.) for accessibility.
- Avoid adding large JS dependencies; use Alpine or Laravel/Livewire when needed.

### 5.3 Backend / Laravel contributions

If you want to work on backend features (admin panel, events management, etc.):

- Follow standard Laravel conventions for routes, controllers, and models.
- Add or update tests when you change backend logic.
- Document any new environment variables or commands in this README.

---

## 6. Project structure (high level)

```text
cyber-web-app/
├── app/                    # Laravel application code (controllers, models, etc.)
├── public/                 # Public entry point (index.php, built assets)
├── resources/
│   ├── css/                # Tailwind entry (app.css)
│   ├── js/                 # JavaScript / Alpine bootstrapping
│   └── views/
│       ├── layouts/        # Layouts, including frontend layout
│       ├── frontend/       # Public website pages & components
│       └── pages/          # Admin/dashboard pages from the TailAdmin base
├── routes/                 # Laravel routes (web.php, api.php, etc.)
├── tests/                  # Automated tests
├── composer.json           # PHP dependencies
├── package.json            # Node dependencies
└── vite.config.js          # Vite/Tailwind build config
```

---

## 7. License

This project is currently based on the TailAdmin Laravel template.
Check the included `LICENSE` file for details before reusing or
redistributing this code outside the SLAU Cybersecurity & Innovations
Club context.
