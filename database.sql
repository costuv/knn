DROP DATABASE IF EXISTS kaustuv_blog;
CREATE DATABASE kaustuv_blog;
USE kaustuv_blog;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    role VARCHAR(10) DEFAULT 'user'
);

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(50) DEFAULT 'General',
    is_breaking BOOLEAN DEFAULT FALSE,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_category ON posts(category);
CREATE INDEX idx_breaking ON posts(is_breaking);

INSERT INTO posts (title, category, is_breaking, content) VALUES 
('Welcome to KNN', 'General', 0, 'Welcome to Kaustuv News Network.'),
('Breaking: Major Technology Update', 'Technology', 1, 'Latest breaking technology news.'),
('Sports News', 'Sports', 0, 'Latest sports updates.'),
('Breaking: World News', 'World', 1, 'Latest breaking world news.');
