## Development Log

** Day1 (milestone 1)
- Project setup 
- Set up GitHub repository and project folder structure.
- Configured local development environment using MAMP (Apache + MySQL + PHP).
- Connected project folder inside MAMP's htdocs directory for local testing with my github desktop . 

** Database Setup & Connection
-  Designed and created the `users` table schema (`database/schema.sql`), including fields for email, hashed password, full name, location, interests, biography, role, and email verification status
- Created the `localconnect` database in MySQL via phpMyAdmin
- Wrote `config/db.php` to establish a PDO-based connection between PHP and MySQL
- Verified the connection with a test script (`test.php`) — confirmed successful connection to the database

**Authentication & Validation
- Implemented user registration with email and required-field validation.
- Added password complexity validation and secure password hashing.
- Implemented user login using password verification and PHP sessions.
- Added protected dashboard access and logout functionality.
- Added authentication and role-based access helper functions.
- Added CSRF protection and XSS-safe output handling.

**Testing
- Added Jest tests for password validation.
- Added Cypress tests for valid and invalid login scenarios.
- Verified protected dashboard access after successful login.