# PRITECH – Mini Issue Tracker

A team issue tracking application built with Laravel 13. Manage projects, issues, tags, and comments with a clean UI and AJAX-powered interactions.

## Features

- **Projects** — Create, edit, delete projects with start date and deadline. Only the project owner can edit or delete.
- **Issues** — Full CRUD with status (`open`, `in_progress`, `closed`) and priority (`low`, `medium`, `high`) filters, tag filter, and debounced live search.
- **Tags** — Create unique tags with a color picker. Attach and detach tags to issues via AJAX modal with no page reload.
- **Comments** — Load comments via AJAX with pagination. Add new comments instantly without reloading the page.
- **Assignees** — Assign multiple team members to an issue via AJAX modal.
- **Authorization** — Only project owners can edit or delete their projects (Laravel Policies).
- **Authentication** — Register, login, and profile management via Laravel Breeze.

## Tech Stack

- **Backend:** Laravel 13, PHP 8.2+
- **Frontend:** Blade templates, Tailwind CSS, Vanilla JavaScript (AJAX/Fetch API)
- **Database:** MySQL
- **Auth:** Laravel Breeze

## Installation

```bash
git clone <repo-url>
cd PRITECH_PROJECT
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
```

Configure your database in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pritech_tracker
DB_USERNAME=root
DB_PASSWORD=
```

Then run migrations and seed demo data:

```bash
php artisan migrate --seed
```

## Demo Accounts

| Name           | Email               | Password |
|----------------|---------------------|----------|
| Alice Owner    | alice@example.com   | password |
| Bob Developer  | bob@example.com     | password |
| Carol Designer | carol@example.com   | password |

## Database Structure

| Table        | Description                          |
|--------------|--------------------------------------|
| `users`      | Authenticated users                  |
| `projects`   | Projects owned by users              |
| `issues`     | Issues belonging to projects         |
| `tags`       | Unique tags with optional color      |
| `comments`   | Comments on issues                   |
| `issue_tag`  | Many-to-many pivot: issues and tags  |
| `issue_user` | Many-to-many pivot: issues and users |

## License

MIT
