CREATE DATABASE task_shelder
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;
USE task_shelder;
CREATE TABLE users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email VARCHAR(128) NOT NULL UNIQUE,
    login VARCHAR(32) NOT NULL UNIQUE,
    password VARCHAR(20) NOT NULL
);
CREATE TABLE projects(
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(32) NOT NULL UNIQUE,
    user_id int,
    FOREIGN KEY (user_id) REFERENCES users (id)
);
CREATE TABLE tasks(
    creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status BOOLEAN DEFAULT(0) NOT NULL,
    title VARCHAR(32) NOT NULL UNIQUE,
    file_path TEXT,
    date DATE,
    user_id int,
    project_id int,
    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (project_id) REFERENCES projects (id)
);