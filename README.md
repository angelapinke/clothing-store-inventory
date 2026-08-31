# clothing-store-inventory

A simple order and inventory management system for small clothing stores. It provides basic product and store records, inventory counts, and order logging — intended for students and instructors. This repository was built as a university course project and is not configured for production deployment (no environment/config management or hardened security).

Tech stack: PHP, MySQL, Bootstrap

<img width="584" height="765" alt="image" src="https://github.com/user-attachments/assets/23a2556c-bcc1-40b1-82f2-245725e3bb43" />

## Data model (textual)

- bolt
  - id INT PRIMARY KEY AUTO_INCREMENT
  - nev VARCHAR(30) — store name
  - cim VARCHAR(30) — address
  - fonok VARCHAR(20) — manager

- ruha
  - id INT PRIMARY KEY AUTO_INCREMENT
  - nev VARCHAR(30) — product name
  - marka VARCHAR(20) — brand
  - kateg VARCHAR(20) — category
  - db INT — quantity/stock (initial)

- rendeles
  - id INT PRIMARY KEY AUTO_INCREMENT
  - boltid INT NOT NULL — FK → bolt(id)
  - ruhaid INT NOT NULL — FK → ruha(id)
  - db INT — ordered quantity
  - datum DATE NOT NULL — order date
  - keszdatum DATE — completion date (nullable)

Relationships:
- rendeles.boltid → bolt.id (many orders per store)
- rendeles.ruhaid → ruha.id (many orders per product)
- In other words: bolt (1) — (N) rendeles and ruha (1) — (N) rendeles.

See the SQL schema and sample data: code/hazi.sql

## Notes
- This is a teaching/demo project. To run it locally you need to create a MySQL database (the schema is in code/hazi.sql), configure the database connection in code/db.php, and ensure your PHP environment has appropriate permissions.
- Do not use this code as-is in production without adding configuration management, validation, and security hardening.
