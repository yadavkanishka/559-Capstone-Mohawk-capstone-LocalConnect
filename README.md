## Development Log

** Day1 
- Project setup 
- Set up GitHub repository and project folder structure.
- Configured local development environment using MAMP (Apache + MySQL + PHP).
- Connected project folder inside MAMP's htdocs directory for local testing with my github desktop . 

** Database Setup & Connection
-  Designed and created the `users` table schema (`database/schema.sql`), including fields for email, hashed password, full name, location, interests, biography, role, and email verification status
- Created the `localconnect` database in MySQL via phpMyAdmin
- Wrote `config/db.php` to establish a PDO-based connection between PHP and MySQL
- Verified the connection with a test script (`test.php`) — confirmed successful connection to the database

