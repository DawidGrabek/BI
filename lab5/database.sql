-- Tworzenie bazy danych
CREATE DATABASE IF NOT EXISTS lab5;
USE lab5;

-- Tabela użytkowników
CREATE TABLE user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    login VARCHAR(30) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Tabela uprawnień
CREATE TABLE privilege (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    id_parent_privilege INT DEFAULT NULL
);

-- Tabela relacji użytkownik-uprawnienie
CREATE TABLE user_privilege (
    id_user INT,
    id_privilege INT,
    FOREIGN KEY (id_user) REFERENCES user(id),
    FOREIGN KEY (id_privilege) REFERENCES privilege(id),
    PRIMARY KEY (id_user, id_privilege)
);

-- Tabela ról
CREATE TABLE role (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL
);

-- Tabela relacji rola-uprawnienie
CREATE TABLE role_privilege (
    id_role INT,
    id_privilege INT,
    FOREIGN KEY (id_role) REFERENCES role(id),
    FOREIGN KEY (id_privilege) REFERENCES privilege(id),
    PRIMARY KEY (id_role, id_privilege)
);

-- Tabela relacji użytkownik-roli
CREATE TABLE user_role (
    id_user INT,
    id_role INT,
    FOREIGN KEY (id_user) REFERENCES user(id),
    FOREIGN KEY (id_role) REFERENCES role(id),
    PRIMARY KEY (id_user, id_role)
);

-- Wstawianie przykładowych danych
INSERT INTO user (login, password) VALUES ('admin', 'admin123'), ('user1', 'password1');
INSERT INTO privilege (name) VALUES ('add message'), ('edit message'), ('delete message'), ('view private');
INSERT INTO role (name) VALUES ('Administrator'), ('Moderator');

-- Przypisanie uprawnień do ról
INSERT INTO role_privilege (id_role, id_privilege) VALUES 
(1, 1), -- Administrator: add message
(1, 2), -- Administrator: edit message
(1, 3), -- Administrator: delete message
(1, 4), -- Administrator: view private
(2, 1), -- Moderator: add message
(2, 4); -- Moderator: view private

-- Przypisanie roli użytkownikom
INSERT INTO user_role (id_user, id_role) VALUES (1, 1), (2, 2);
