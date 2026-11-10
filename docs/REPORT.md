# Project Report

**Module:** SE102.3 Web Based Application Development
**Assessment:** Web Design (Repeat Coursework)
**Project:** Daily Pulse News - Online News Website with Admin Features
**Student:** <STUDENT NAME> | **Index No:** <INDEX NUMBER>
**GitHub:** https://github.com/kariyawasamp679-create/news-portal-web-app

---

## 1. Introduction

Daily Pulse News is a dynamic online news portal. Readers can browse the latest stories, filter by category, search, and comment. Editors and administrators use a protected admin panel to publish and manage content. The website is backed by a MySQL database and supports all DML operations (INSERT, SELECT, UPDATE, DELETE).

## 2. Proposal

**Problem.** Small news organisations need a simple way to publish stories online without editing code.

**Objectives**
1. Provide a clean, responsive public news website.
2. Provide an admin panel for managing articles, categories, comments, messages and users.
3. Store all data in a relational database.
4. Use GitHub for version control and to show contributions.

**Scope.** Public pages: Home, Category, Article, Search, About Us, Contact Us. Admin pages: Login, Dashboard, Articles, Categories, Comments, Messages, Users.

**Technologies.** PHP 8 (PDO), MySQL/MariaDB, HTML5, CSS3, XAMPP (Apache), Git and GitHub, Visual Studio Code.

**Users**
- *Visitor* - reads, searches, comments, sends messages.
- *Editor* - logs in and manages articles, categories, comments and messages.
- *Admin* - everything an editor can do, plus user management.

## 3. UI Plan (Web Design)

**Layout.** A consistent header (logo + search), a dark navigation bar with category links, a main content area and a footer.
- Colour scheme: red `#c8102e` (accent), charcoal `#1b1f27` (navigation/footer), light grey `#f3f4f6` (panels), white background.
- Typography: Segoe UI / Arial, bold headings with a red left border for section titles.
- Responsive: three-column grids collapse to a single column on screens narrower than 800px.

**Wireframe - Home page**
```
+------------------------------------------------+
| Logo                      [ Search........ ][Go]|
| Home World Sports Tech Business Ent. About Contact Admin |
+------------------------------------------------+
| TOP STORIES                                    |
| [card] [card] [card]                           |
+--------------------------------+---------------+
| LATEST NEWS                    | MOST READ     |
| [card] [card]                  | 1. ...        |
| [card] [card]                  | 2. ...        |
+--------------------------------+---------------+
| Footer                                         |
+------------------------------------------------+
```

**Site map**
```
Home
 |- Category (World, Sports, Technology, Business, Entertainment)
 |    '- Article (+ comments)
 |- Search results
 |- About Us
 |- Contact Us
 '- Admin Login
      '- Dashboard
           |- Articles (list, add, edit, delete)
           |- Categories (add, edit, delete)
           |- Comments (delete)
           |- Messages (delete)
           '- Users (add, change role, delete)
```

## 4. Website GUI

*(Insert screenshots here - see section 8 for the list of screenshots to capture.)*

| Page | URL | Description |
|------|-----|-------------|
| Home | `/index.php` | Top stories, latest news, most-read sidebar |
| Category | `/category.php?slug=sports` | Articles in one category |
| Article | `/article.php?id=1` | Full story, view counter, comments |
| Search | `/search.php?q=...` | Results across title, summary and content |
| About Us | `/about.php` | Information about the organisation |
| Contact Us | `/contact.php` | Form that saves a message |
| Admin Login | `/login.php` | Username and password login |
| Dashboard | `/admin/index.php` | Totals and recent articles |
| Manage Articles | `/admin/articles.php` | Table with edit and delete |
| Add/Edit Article | `/admin/article_form.php` | Form with image upload |
| Categories | `/admin/categories.php` | Add, rename, delete |
| Comments / Messages | `/admin/comments.php`, `/admin/messages.php` | Moderation |
| Users | `/admin/users.php` | Add, change role, delete |

## 5. Database Design

Database name: `news_portal` (engine InnoDB, utf8mb4).

### 5.1 Tables

**users**
| Column | Type | Notes |
|--------|------|-------|
| id | INT, PK, AUTO_INCREMENT | |
| username | VARCHAR(50), UNIQUE | |
| email | VARCHAR(100), UNIQUE | |
| password | VARCHAR(255) | bcrypt hash |
| role | ENUM('admin','editor') | default editor |
| created_at | TIMESTAMP | |

**categories**
| Column | Type | Notes |
|--------|------|-------|
| id | INT, PK, AUTO_INCREMENT | |
| name | VARCHAR(60), UNIQUE | |
| slug | VARCHAR(80), UNIQUE | used in URLs |

**articles**
| Column | Type | Notes |
|--------|------|-------|
| id | INT, PK, AUTO_INCREMENT | |
| title | VARCHAR(200) | |
| summary | VARCHAR(300) | |
| content | TEXT | |
| image | VARCHAR(255), NULL | file name in /uploads |
| category_id | INT, FK -> categories(id) | ON DELETE CASCADE |
| author_id | INT, FK -> users(id) | ON DELETE CASCADE |
| is_featured | TINYINT(1) | shown in Top Stories |
| views | INT | incremented on each view |
| created_at | TIMESTAMP | |

**comments**
| Column | Type | Notes |
|--------|------|-------|
| id | INT, PK, AUTO_INCREMENT | |
| article_id | INT, FK -> articles(id) | ON DELETE CASCADE |
| name | VARCHAR(80) | |
| body | TEXT | |
| created_at | TIMESTAMP | |

**messages**
| Column | Type | Notes |
|--------|------|-------|
| id | INT, PK, AUTO_INCREMENT | |
| name, email, subject | VARCHAR | |
| message | TEXT | |
| created_at | TIMESTAMP | |

### 5.2 Relationships (ER description)
```
users 1 ---- * articles * ---- 1 categories
                  1
                  |
                  *
              comments

messages (stand-alone)
```
- One user writes many articles.
- One category contains many articles.
- One article has many comments.

### 5.3 DML operations used

| Operation | Example | File |
|-----------|---------|------|
| INSERT | `INSERT INTO articles (...) VALUES (?,...)` | admin/article_form.php |
| INSERT | `INSERT INTO comments ...` | article.php |
| INSERT | `INSERT INTO messages ...` | contact.php |
| SELECT | `SELECT a.*, c.name FROM articles a JOIN categories c ...` | index.php, category.php |
| SELECT | `... WHERE title LIKE ? OR summary LIKE ? OR content LIKE ?` | search.php |
| UPDATE | `UPDATE articles SET title=?, ... WHERE id=?` | admin/article_form.php |
| UPDATE | `UPDATE articles SET views = views + 1` | article.php |
| DELETE | `DELETE FROM articles WHERE id = ?` | admin/articles.php |
| DELETE | categories, users, comments, messages | admin/*.php |

## 6. Security

- Passwords stored with `password_hash()` and checked with `password_verify()`.
- All SQL uses PDO prepared statements (prevents SQL injection).
- All output escaped with `htmlspecialchars()` (prevents XSS).
- CSRF token on every state-changing form.
- Admin pages require a login session; user management is limited to the admin role.
- Image uploads limited by extension and size, saved with random file names.

## 7. GitHub and Contributions

Repository: https://github.com/kariyawasamp679-create/news-portal-web-app

Work was committed in stages (database, public pages, admin panel, styling, documentation). The commit history and the Insights > Contributors page show the contribution timeline.

## 8. Testing

| Test | Result |
|------|--------|
| All public pages load without errors | Pass |
| Admin login with correct / wrong password | Pass |
| Admin pages redirect to login when not logged in | Pass |
| Create, edit, delete article | Pass |
| Add category / add comment / send contact message | Pass |

**Screenshots to capture for the final report:** Home, Category, Article with comments, Contact, Login, Dashboard, Articles list, Add article form, Categories, Users, and phpMyAdmin showing the tables.

## 9. Conclusion

The project delivers a complete, database-driven news website with a secure admin panel and full DML support. Possible future work: rich-text editor, article scheduling, pagination, and user registration for readers.

## 10. How to run

See `README.md`. Admin login: `admin` / `Admin@123`.
