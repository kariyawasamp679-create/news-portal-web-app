-- Daily Pulse News - database schema + sample data
-- Import this file in phpMyAdmin (Import tab) or run: mysql -u root < database/schema.sql

CREATE DATABASE IF NOT EXISTS news_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE news_portal;

DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS articles;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','editor') NOT NULL DEFAULT 'editor',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(60) NOT NULL UNIQUE,
    slug VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    summary VARCHAR(300) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    category_id INT NOT NULL,
    author_id INT NOT NULL,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    views INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    name VARCHAR(80) NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Default admin: username "admin", password "Admin@123"
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@dailypulse.test', '$2y$12$utdRj.Eu4TMn/.f2y9LduuNY4uxRLa.yLtxn1r70A0pKp7pO787H2', 'admin');

INSERT INTO categories (name, slug) VALUES
('World', 'world'), ('Sports', 'sports'), ('Technology', 'technology'),
('Business', 'business'), ('Entertainment', 'entertainment');

INSERT INTO articles (title, summary, content, category_id, author_id, is_featured) VALUES
('Global leaders meet to discuss climate action', 'World leaders gather for a summit focused on cutting emissions over the next decade.', 'Leaders from more than 40 countries met this week to negotiate new targets for reducing carbon emissions.\n\nDelegates said the talks were constructive, with several nations pledging additional funding for renewable energy projects.\n\nA final agreement is expected before the end of the month.', 1, 1, 1),
('National cricket team wins thrilling series decider', 'A last-over finish sealed the series for the home side in front of a packed stadium.', 'In a nail-biting finish, the home side chased down the target with two balls to spare.\n\nThe captain praised the bowlers for restricting the opposition in the middle overs.', 2, 1, 1),
('New AI tools are changing how students learn', 'Universities are adapting as artificial intelligence becomes part of everyday study.', 'Educators say AI assistants can help with research and revision, but warn against over-reliance.\n\nSeveral universities have published new guidelines on responsible use of these tools.', 3, 1, 1),
('Local startups attract record investment this year', 'Venture funding for regional startups has reached its highest level in five years.', 'Analysts credit a growing pool of skilled graduates and improved digital infrastructure.\n\nFounders say the next challenge is scaling beyond the home market.', 4, 1, 0),
('Film festival opens with a celebration of local cinema', 'Over 60 films will be screened during the ten-day festival.', 'The festival opened on Friday evening with a gala screening and a tribute to veteran directors.\n\nOrganisers expect record attendance this year.', 5, 1, 0),
('Smartphone makers focus on battery life in new releases', 'Longer battery life is the headline feature for this season''s phones.', 'Manufacturers are adopting new battery chemistries and smarter power management.\n\nReviewers say real-world endurance has improved noticeably over last year.', 3, 1, 0);

INSERT INTO comments (article_id, name, body) VALUES
(1, 'Nimal', 'Hopefully this leads to real action.'),
(2, 'Kasun', 'What a match! Great game.');
