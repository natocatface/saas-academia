-- ============================================================
-- Script de creación de la base de datos para Academia SaaS
-- Ejecuta esto UNA vez en MySQL antes de correr las migraciones.
--   mysql -u root -p < database/crear_base_datos.sql
-- ============================================================
CREATE DATABASE IF NOT EXISTS saas_academia
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- (Opcional) crear un usuario dedicado en lugar de usar root:
-- CREATE USER IF NOT EXISTS 'academia'@'localhost' IDENTIFIED BY 'academia123';
-- GRANT ALL PRIVILEGES ON saas_academia.* TO 'academia'@'localhost';
-- FLUSH PRIVILEGES;

USE saas_academia;
-- Las tablas las crea Laravel con: php artisan migrate
