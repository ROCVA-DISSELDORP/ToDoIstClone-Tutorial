CREATE DATABASE IF NOT EXISTS todoist_db;
USE todoist_db;

-- 1. Tabellen aanmaken (zoals eerder)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    color VARCHAR(7) DEFAULT '#808080',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    project_id INT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    due_date DATE,
    is_completed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL
);

-- 2. Proefgegevens toevoegen

-- Voeg een testgebruiker toe
-- Wachtwoord is 'password' (gehasht met BCRYPT)
INSERT INTO users (id, name, email, password) VALUES 
(1, 'Jan de Tester', 'test@test.nl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Voeg wat projecten toe voor deze gebruiker
INSERT INTO projects (id, user_id, name, color) VALUES 
(1, 1, '🚀 Werk', '#db4c3f'),
(2, 1, '🏠 Privé', '#2486ff'),
(3, 1, '🛒 Boodschappen', '#ff9a00'),
(4, 1, 'Fitness', '#058527');

-- Voeg taken toe
INSERT INTO tasks (user_id, project_id, title, description, due_date, is_completed) VALUES 
-- Taken voor vandaag (Werk)
(1, 1, 'Email beantwoorden naar klant', 'Belangrijke offerte opsturen', CURDATE(), 0),
(1, 1, 'Meeting voorbereiden', 'Presentatie slides nakijken', CURDATE(), 0),

-- Taken voor vandaag (Privé)
(1, 2, 'Huisarts bellen', 'Afspraak maken voor controle', CURDATE(), 0),
(1, 2, 'Plantjes water geven', NULL, CURDATE(), 1), -- Al voltooid

-- Taken zonder project (Inbox)
(1, NULL, 'Nieuwe schoenen kopen', 'Kijken bij de Nike store', DATE_ADD(CURDATE(), INTERVAL 2 DAY), 0),
(1, NULL, 'Vuilnis buiten zetten', NULL, CURDATE(), 0),

-- Taken voor de toekomst (Boodschappen)
(1, 3, 'Melk en brood halen', NULL, DATE_ADD(CURDATE(), INTERVAL 1 DAY), 0),
(1, 3, 'Appels', 'Alleen de rode!', DATE_ADD(CURDATE(), INTERVAL 1 DAY), 0);