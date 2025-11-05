-- Create database
CREATE DATABASE IF NOT EXISTS news_website;
USE news_website;

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create news table
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    category_id INT,
    image VARCHAR(255),
    author VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Insert sample categories
INSERT INTO categories (name) VALUES 
('Politics'),
('Technology'),
('Sports'),
('Entertainment'),
('Business');

-- Insert sample news articles
INSERT INTO news (title, content, category_id, author, image) VALUES
('New Technology Revolution', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies tincidunt, nunc nisl aliquam nisl, eget ultricies nisl nisl eget nisl.', 2, 'John Doe', 'tech1.jpg'),
('Sports Championship Results', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies tincidunt, nunc nisl aliquam nisl, eget ultricies nisl nisl eget nisl.', 3, 'Jane Smith', 'sports1.jpg'),
('Political Summit Concludes', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies tincidunt, nunc nisl aliquam nisl, eget ultricies nisl nisl eget nisl.', 1, 'Robert Johnson', 'politics1.jpg');