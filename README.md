# Laravel Portfolio CMS

A modern, full-stack personal portfolio and content management system built with **Laravel 13**, **Vue 3**, **Inertia.js**, and **Tailwind CSS**. Features a sleek public-facing portfolio website with a powerful admin dashboard for managing all content.

---

## Table of Contents

1. [Overview](#overview)
2. [Tech Stack](#tech-stack)
3. [Features](#features)
4. [Project Structure](#project-structure)
5. [Database Schema](#database-schema)
6. [Installation](#installation)
7. [Development](#development)
8. [Admin Panel](#admin-panel)
9. [Frontend Sections](#frontend-sections)
10. [API & Services](#api--services)
11. [Testing](#testing)
12. [Code Quality](#code-quality)

---

## Overview

This is a **single-page portfolio website** with a complete **Admin Dashboard** for managing:

- Hero banners / sliders
- About me / personal details
- Work experience & education
- Skills with proficiency levels
- Projects portfolio with feature flags
- Client testimonials
- Blog posts
- Contact messages with reply system

The public site uses **server-side rendering via Inertia.js** with cached data for optimal performance.

---

## Tech Stack

### Backend
| Technology | Version | Purpose |
|------------|---------|---------|
| PHP | ^8.3 | Server language |
| Laravel | ^13.0 | Web framework |
| Inertia.js | ^3.0 | SPA without API |
| Laravel Sanctum | ^4.0 | Authentication |
| Intervention Image | ^4.0 | Image processing |
| Spatie Sluggable | ^3.8 | URL slugs |
| SQLite | — | Default database |

### Frontend
| Technology | Version | Purpose |
|------------|---------|---------|
| Vue.js | ^3.5.13 | UI framework |
| TypeScript | ^5.6.3 | Type safety |
| Tailwind CSS | ^4.1.1 | Utility-first CSS |
| Vite | ^8.0.0 | Build tool |
| Ziggy | ^2.0 | Named routes in JS |
| Heroicons Vue | ^2.2.0 | Icon library |
| VueUse | ^12.8.2 | Composition utilities |

### Development Tools
| Tool | Purpose |
|------|---------|
| PestPHP | Testing framework |
| Laravel Pint | PHP linting |
| ESLint | JS/Vue linting |
| Prettier | Code formatting |
| Vue TSC | TypeScript checking |

---

## Features

### Public Website (Frontend)
- **Hero Section** — Carousel banners with CTA buttons
- **About Section** — Personal bio with photo and resume download
- **Experience Timeline** — Work history with company details
- **Education Section** — Academic background
- **Skills Showcase** — Proficiency bars with icons
- **Featured Projects** — Portfolio grid with links to live demos & repositories
- **Testimonials** — Client reviews with star ratings
- **Recent Blogs** — Latest articles with dates and images
- **Contact Form** — Public contact submission

### Admin Dashboard
- **Authentication** — Laravel Breeze auth with admin gate
- **Banner Management** — CRUD with sorting
- **About Management** — Personal info, experiences, educations (JSON fields)
- **Skills Management** — CRUD with proficiency and icons
- **Projects Management** — Full CRUD with featured flag, skills attachment, image upload
- **Blog Management** — Article publishing with views counter
- **Testimonials Management** — Client reviews with ratings
- **Contact Inbox** — Read/unread status, reply via email
- **Profile Settings** — User profile management

### Technical Features
- **Data Caching** — Homepage data cached for 1 hour
- **Image Uploads** — Organized storage in `public/uploads/`
- **SEO Ready** — Meta tags via `SeoHead` component
- **Scoped Publishing** — All content supports draft/published states
- **Soft Features** — Featured flags, sort ordering, view counts
- **Type Safety** — Full TypeScript coverage on frontend
- **Flash Messages** — Toast notifications

---

## Project Structure

```
portfolio/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin CRUD controllers
│   │   │   ├── Auth/           # Authentication controllers
│   │   │   └── Frontend/       # Public page controllers
│   │   ├── Middleware/
│   │   │   └── HandleInertiaRequests.php
│   │   └── Requests/           # Form request validation
│   ├── Models/
│   │   ├── About.php
│   │   ├── Banner.php
│   │   ├── Blog.php
│   │   ├── Contact.php
│   │   ├── Project.php
│   │   ├── Skill.php
│   │   ├── Testimonial.php
│   │   └── User.php
│   ├── Services/
│   │   ├── Admin/              # Admin business logic
│   │   ├── Frontend/           # Public site data services
│   │   └── ImageService.php
│   └── Mail/
│       └── ContactReplyMail.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.ts              # Inertia app entry
│   │   ├── Components/
│   │   │   ├── Admin/          # Admin UI components
│   │   │   └── Frontend/       # Public page sections
│   │   ├── Layouts/
│   │   │   ├── AdminLayout.vue
│   │   │   ├── FrontLayout.vue
│   │   │   └── AuthenticatedLayout.vue
│   │   ├── pages/
│   │   │   ├── Admin/          # Admin page components
│   │   │   ├── Auth/           # Login/register pages
│   │   │   └── Front/          # Public pages (Home)
│   │   └── types/
│   └── views/
│       └── app.blade.php       # Inertia root template
├── routes/
│   ├── web.php
│   └── auth.php
├── public/
│   └── uploads/                # User-uploaded images
├── tests/
│   ├── Feature/
│   └── Unit/
├── composer.json
├── package.json
├── vite.config.ts
├── tsconfig.json
└── phpunit.xml
```

---

## Database Schema

### Core Tables

| Table | Key Columns | Relations |
|-------|-------------|-----------|
| **users** | id, name, email, is_admin | — |
| **banners** | title, subtitle, image_path, cta_text, cta_url, status, sort_order, published_at | — |
| **abouts** | heading, content, photo_path, resume_url, status, name, email, mobile, address, experiences[], educations[] | — |
| **skills** | name, slug, proficiency, icon, status, sort_order | belongsToMany projects |
| **projects** | title, slug, summary, description, thumbnail_path, project_url, repository_url, started_at, completed_at, is_featured, status, published_at | belongsToMany skills |
| **blogs** | title, slug, excerpt, content, featured_image, cover_image, status, published_at, views_count | — |
| **testimonials** | client_name, client_role, company, avatar_path, quote, rating, status, sort_order | — |
| **contacts** | name, email, phone, subject, message, is_read, responded_at, status | — |
| **project_skill** | project_id, skill_id | Pivot table |

### JSON Fields
- `abouts.experiences` — Array of work experience objects
- `abouts.educations` — Array of education objects

---

## Installation

### Prerequisites
- PHP >= 8.3
- Composer
- Node.js & npm/pnpm
- SQLite (or configure another database in `.env`)

### Quick Setup
```bash
# Run automated setup
composer run setup

# Or manually:
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```

### Create Admin User
```bash
php artisan tinker
>>> \App\Models\User::factory()->create(['email' => 'admin@example.com', 'is_admin' => true]);
```

---

## Development

### Start Development Servers
```bash
composer run dev
```
Runs concurrently:
- Laravel server (`php artisan serve`)
- Queue worker (`php artisan queue:listen`)
- Log watcher (`php artisan pail`)
- Vite dev server (`npm run dev`)

### Build for Production
```bash
npm run build
```

### Code Quality Checks
```bash
# PHP linting
composer run lint

# PHP lint check (CI)
composer run lint:check

# JS/Vue linting
npm run lint

# Type checking
npm run types:check

# Formatting
npm run format

# Run tests
composer run test
```

---

## Admin Panel

Access the admin panel at `/admin` after logging in with an admin account.

### Routes
| Route | Controller | Action |
|-------|------------|--------|
| `GET /admin` | DashboardController | Admin dashboard |
| `GET /admin/banners` | BannerController | List banners |
| `GET /admin/abouts` | AboutController | Manage about |
| `GET /admin/skills` | SkillController | List skills |
| `GET /admin/projects` | ProjectController | List projects |
| `GET /admin/blogs` | BlogController | List blogs |
| `GET /admin/testimonials` | TestimonialController | List testimonials |
| `GET /admin/contacts` | ContactController | Contact inbox |

### Authorization
- `auth` middleware for login
- `can:accessAdmin` gate (requires `is_admin = true` on user)

---

## Frontend Sections

The homepage (`/`) renders a single Inertia page composed of these Vue sections:

| Section | Component | Data Source |
|---------|-----------|-------------|
| Hero | `HeroSection.vue` | banners |
| About | `AboutSection.vue` | about |
| Experience | `ExperienceSection.vue` | experiences (JSON) |
| Education | `EducationSection.vue` | educations (JSON) |
| Skills | `SkillsSection.vue` | skills |
| Projects | `ProjectsSection.vue` | featured_projects |
| Testimonials | `TestimonialsSection.vue` | testimonials |
| Blog | `BlogSection.vue` | recent_blogs |
| Contact CTA | `ContactCtaSection.vue` | static |

All data is eager-loaded and cached via `PortfolioService::getHomeData()`.

---

## API & Services

### PortfolioService
Located at `app/Services/Frontend/PortfolioService.php`

| Method | Description |
|--------|-------------|
| `getHomeData()` | Returns cached array of all homepage data |

**Cache Key:** `portfolio_home_data`  
**TTL:** 3600 seconds (1 hour)

### ImageService
Handles image uploads with organized storage in `public/uploads/{type}/`.

### Mail
- `ContactReplyMail` — Sends email replies to contact form submissions

---

## Testing

This project uses **PestPHP** for testing.

```bash
# Run all tests
php artisan test

# Run via composer
composer run test
```

### Test Structure
```
tests/
├── Feature/
│   ├── Admin/         # Admin panel tests
│   ├── Auth/          # Authentication tests
│   ├── Frontend/      # Public site tests
│   └── Models/        # Model tests
└── Unit/
    └── ExampleTest.php
```

---

## Code Quality

| Tool | Command | Purpose |
|------|---------|---------|
| Laravel Pint | `composer run lint` | PHP code style |
| ESLint | `npm run lint` | JS/Vue linting |
| Prettier | `npm run format` | Code formatting |
| Vue TSC | `npm run types:check` | TypeScript validation |

### CI Pipeline
```bash
composer run ci:check
```
Runs: lint check → format check → type check → tests

---

## License

This project is open-sourced under the [MIT License](LICENSE).

