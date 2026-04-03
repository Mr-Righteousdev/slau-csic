# 🚀 Project Contribution Guide

Welcome to the project! Please follow these rules to keep our code clean, consistent, and production-ready.

---

## 📌 Branching Strategy

* `main` → production-ready code (protected)
* `develop` → active development (protected)
* `feature/*` → new features
* `fix/*` → bug fixes

---

## 🔄 Development Workflow

1. Pull latest changes:

   ```bash
   git checkout develop
   git pull origin develop
   ```

2. Create a new branch:

   ```bash
   git checkout -b feature/your-feature-name
   ```

3. Work on your feature.

4. Commit changes:

   ```bash
   git add .
   git commit -m "feat: short description"
   ```

5. Push your branch:

   ```bash
   git push origin feature/your-feature-name
   ```

6. Open a Pull Request to `develop`.

---

## ✅ Pull Request Rules

* Must be reviewed before merging
* Keep PRs small and focused
* Add a clear description
* Link related issues if applicable

---

## ❌ запрещено (NOT ALLOWED)

* Direct commits to `main` or `develop`
* Pushing broken code
* Large, messy commits

---

## 🧹 Code Standards

* Follow Laravel conventions
* Use meaningful variable names
* Keep controllers thin
* Use services for logic

---

## 🛠 Setup Instructions

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
npm install && npm run dev
```

---

## 🧪 Testing

Run tests before pushing:

```bash
php artisan test
```

---

## 💬 Communication

* Use issues for tasks
* Ask before implementing major changes
* Respect code reviews

---

Let’s build something clean, scalable, and professional 🚀
