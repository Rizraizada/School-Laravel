# School Management + CMS (Laravel + Blade)

This project is a full Laravel rebuild of your school platform with:

- Public school website (home, notices, gallery, committee, staff, messages, contact)
- Admin/teacher dashboard with role-based access
- Student, class, section, and teacher-section management
- Detailed attendance and quick attendance
- CMS modules (slider, awards, gallery, directors, activities, notices)

The UI follows a blue government-style visual theme inspired by:
`https://comillaboard.portal.gov.bd/`

---

## 1) Tech Stack

- **Backend:** Laravel 13, Eloquent ORM
- **Frontend:** Blade templates (no SPA dependency)
- **Database:** SQLite by default (easy local run), compatible with MySQL
- **Authentication:** Session-based auth with username + password login
- **Authorization:** Role middleware (`admin`, `headmaster`, `teacher`)
- **Uploads:** Laravel `public` disk (`storage/app/public`)

---

## 2) Core Modules Included

### Public Website
- Home page (highlights, latest notices, activities, awards)
- Notices listing
- Gallery listing
- Committee/board listing
- Staff listing
- Headmaster message
- Contact info page

### Dashboard / Admin
- Dashboard statistics and recent activity widgets
- Users (admin/headmaster/teacher CRUD)
- Classes CRUD
- Sections CRUD
- Teacher-Section assignment CRUD
- Students CRUD
- Attendance bulk entry + report
- Quick attendance CRUD
- Notices CRUD
- Slider CRUD
- Awards CRUD
- Gallery CRUD
- Board directors CRUD
- Activities CRUD

---

## 3) Role Access Rules

- **Teacher / Headmaster / Admin**
  - Students
  - Attendance
  - Quick attendance
  - Dashboard

- **Headmaster / Admin only**
  - Users
  - Classes / Sections
  - Teacher assignments
  - All CMS modules (notice, slider, award, gallery, directors, activities)

Middleware used:
- `auth`
- custom role middleware: `role:headmaster,admin` etc.

---

## 4) Database Schema (Implemented)

- `users`
- `classes`
- `sections`
- `teacher_sections`
- `students`
- `attendances`
- `quick_attendances`
- `notices`
- `sliders`
- `awards`
- `galleries`
- `board_directors`
- `activities`

Also default Laravel tables:
- `password_reset_tokens`
- `sessions`
- `jobs`, `failed_jobs`, `job_batches`
- `cache`, `cache_locks`

---

## 5) Default Login Credentials

Seeded in `DatabaseSeeder`:

- **Admin**
  - username: `admin`
  - password: `admin12345`

- **Headmaster**
  - username: `headmaster`
  - password: `head12345`

- **Teacher**
  - username: `teacher`
  - password: `teacher12345`

---

## 6) Local Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Open: `http://127.0.0.1:8000`

---

## 7) Main Route Map (Web)

### Public
- `/`
- `/notices`
- `/gallery`
- `/committee`
- `/staff`
- `/messages`
- `/contact`

### Auth
- `/login` (GET, POST)
- `/logout` (POST)

### Dashboard
- `/dashboard`

### Teacher/Admin shared
- `/students/*`
- `/attendance`
- `/attendance/bulk`
- `/attendance/report`
- `/quick-attendance/*`

### Admin-only prefix
- `/admin/users/*`
- `/admin/classes/*`
- `/admin/sections/*`
- `/admin/teacher-sections/*`
- `/admin/notices/*`
- `/admin/sliders/*`
- `/admin/awards/*`
- `/admin/galleries/*`
- `/admin/directors/*`
- `/admin/activities/*`

---

## 8) File/Folder Guide

### Controllers
- `app/Http/Controllers/PublicSiteController.php`
- `app/Http/Controllers/AuthController.php`
- `app/Http/Controllers/DashboardController.php`
- `app/Http/Controllers/Admin/*`

### Models
- `app/Models/User.php`
- `app/Models/SchoolClass.php`
- `app/Models/Section.php`
- `app/Models/TeacherSection.php`
- `app/Models/Student.php`
- `app/Models/Attendance.php`
- `app/Models/QuickAttendance.php`
- `app/Models/Notice.php`
- `app/Models/Slider.php`
- `app/Models/Award.php`
- `app/Models/Gallery.php`
- `app/Models/BoardDirector.php`
- `app/Models/Activity.php`

### Middleware
- `app/Http/Middleware/EnsureRole.php`

### Views
- Public views: `resources/views/public/*`
- Auth: `resources/views/auth/login.blade.php`
- Dashboard layout: `resources/views/layouts/dashboard.blade.php`
- Public layout: `resources/views/layouts/app.blade.php`
- Admin CRUD: `resources/views/admin/*`

---

## 9) Rebuild/Customization Notes

If you want to further match the exact Comilla Board visual style:
- refine typography and spacing
- add Bengali content labels
- implement multi-level nav and ticker notices
- add homepage banner animation and card hover effects

If you need API compatibility with old Next.js endpoints, next step is adding:
- `routes/api.php` endpoint map
- API controllers/resources
- token/cookie bridge auth

---

## 10) Testing

Included feature tests:
- Public pages load test
- Login + dashboard access flow test

Run:

```bash
php artisan test
```
