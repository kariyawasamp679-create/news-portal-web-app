# Daily Pulse News - News Portal with Admin Panel

SE102.3 Web Based Application Development - Web Design coursework (repeat).

A dynamic news website built with **PHP 8, MySQL (PDO), HTML5 and CSS3**. Visitors can read and search news, browse by category, comment on stories and contact the newsroom. Staff log in to an admin panel to manage all content.

## Features

**Public site**
- Home (top stories, latest news, most read)
- Category pages, single article page with comments, search
- About Us and Contact Us (messages saved in the database)

**Admin panel** (`/login.php`)
- Secure login (hashed passwords, sessions, CSRF tokens)
- Dashboard with statistics
- Articles: add / view / edit / delete (with image upload)
- Categories: add / edit / delete
- Comments and contact messages: view / delete
- Users: add / change role / delete (admin only)

## DML operations

| Operation | Where |
|-----------|-------|
| INSERT | add article, category, user; post comment; send contact message |
| SELECT | every page (joins across articles, categories, users, comments) |
| UPDATE | edit article, category, user role; article view counter |
| DELETE | delete article, category, user, comment, message |

## Setup (XAMPP)

1. Copy this folder to `C:\xampp\htdocs\news-portal`.
2. Start **Apache** and **MySQL** in the XAMPP Control Panel.
3. Open phpMyAdmin, go to **Import**, choose `database/schema.sql` and click **Go**.
4. Visit <http://localhost/news-portal/>.
5. Admin login: <http://localhost/news-portal/login.php>
   - Username: `admin`
   - Password: `Admin@123`

If your MySQL uses another port or a root password, copy `config.local.example.php` to `config.local.php` and edit it.

## Folder structure

```
admin/        admin panel pages
assets/css/   stylesheet
database/     schema.sql (tables + sample data)
docs/         project report
includes/     shared header, footer, article card
uploads/      uploaded article images
```

## Database

Tables: `users`, `categories`, `articles`, `comments`, `messages` (see `docs/REPORT.md` for the ER description).
