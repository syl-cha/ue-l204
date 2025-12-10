-- requêtes nécessaires à l'accès à la base universite1 par l'application
-- v 0.1
-- 2025-12-10

CREATE USER 'user204'@'localhost' IDENTIFIED BY 'UE-L204';
GRANT SELECT, INSERT, UPDATE, DELETE ON universite1.* TO 'user204'@'localhost';
FLUSH PRIVILEGES;