-- requêtes nécessaires à l'accès à la base universite1 par l'application
-- v 0.1
-- 2025-12-10

-- création de la base si elle n'existe pas
CREATE DATABASE IF NOT EXISTS universite1 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;
-- utilisateur de la base pour l'application
CREATE USER IF NOT EXISTS 'user204'@'localhost' IDENTIFIED BY 'UE-L204';
GRANT SELECT, INSERT, UPDATE, DELETE ON universite1.* TO 'user204'@'localhost';
FLUSH PRIVILEGES;