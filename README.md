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

----------------------------------------------------------------------------------------------------------------------------------------------------------------------
** Day2 (milestone 2)
- Added config/config.php to start sessions and load all shared files in one place.
- Added includes/db_init.php to create the posts, requests, password_reset_tokens and error_logs tables automatically.
- Added sample data (an admin, 3 sample users and 4 sample posts) so the site is not empty when testing.

**User Profile
- Added a "My Profile" page (profile.php) where a logged-in user can update their name, location, skills, interests and biography.
- Added a public profile page (user-profile.php) that shows a member's public info and their posts.
- Added profile validation (length limits for each field) and a "Profile not found" page for bad links.

**Collaboration Posts
- Added create post (post-create.php) with title, description, required skills and location.
- Added a posts page (posts.php) that shows all posts as cards.
- Added edit post (post-edit.php) and delete post (post-delete.php).
- Users can only edit or delete their own posts, and logged-out users cannot create posts.

**Search, Filter & Sort
- Added keyword search on post title and description.
- Added filters for location and skill.
- Added sorting by newest, oldest or location.
- Added a "no results" message when nothing matches.

**Dashboard & Navigation
- Dashboard now shows the user's post count and their 5 latest posts.
- Home page shows the 3 latest posts.
- Navigation bar changes depending on whether the user is logged in.

**Testing
- Added Jest tests for profile validation (empty name and too-long fields).
- Added Cypress tests for the profile page (open, save, and data stays after reload).
- Added Cypress tests for posts (create, edit, delete, and blocking other users' posts).
