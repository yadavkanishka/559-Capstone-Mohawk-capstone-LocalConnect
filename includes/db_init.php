<?php
// LocalConnect - Schema creation & data seeding (runs once, self-healing)

function ensure_schema() {
    $flag = __DIR__ . '/../.db_initialized';
    if (file_exists($flag)) return;

    $pdo = getDBConnection();

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        full_name VARCHAR(150) NOT NULL,
        location VARCHAR(100) DEFAULT '',
        skills TEXT,
        interests TEXT,
        biography TEXT,
        role ENUM('user','admin') DEFAULT 'user',
        email_verified BOOLEAN DEFAULT FALSE,
        failed_attempts INT DEFAULT 0,
        lockout_until DATETIME NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(200) NOT NULL,
        description TEXT NOT NULL,
        required_skills TEXT,
        location VARCHAR(100) DEFAULT '',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        post_id INT NOT NULL,
        sender_id INT NOT NULL,
        receiver_id INT NOT NULL,
        message TEXT,
        status ENUM('pending','accepted','declined') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
        FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS password_reset_tokens (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        token VARCHAR(255) NOT NULL,
        expires_at DATETIME NOT NULL,
        used BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS error_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        level VARCHAR(20),
        message TEXT,
        context TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    seed_data($pdo);
    file_put_contents($flag, date('c'));
}

function seed_data($pdo) {
    $admin_email = getenv('ADMIN_EMAIL') ?: 'admin@localconnect.com';
    $admin_pass = getenv('ADMIN_PASSWORD') ?: 'Admin@123';

    $exists = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $exists->execute([$admin_email]);

    if (!$exists->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, full_name, location, skills, interests, biography, role, email_verified)
            VALUES (?,?,?,?,?,?,?, 'admin', 1)");

        $stmt->execute([
            $admin_email,
            password_hash($admin_pass, PASSWORD_BCRYPT),
            'LocalConnect Admin',
            'Hamilton, ON',
            'Community Management, Moderation',
            'Local events, Volunteering',
            'Administrator account for LocalConnect.'
        ]);
    }

    // Sample users + posts (only if we have just the admin so far)
    $count = (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

    if ($count > 1) return;

    $samples = [
        ['maya@example.com', 'Maya Fernandez', 'Toronto, ON', 'React, UI/UX Design, Figma', 'Community apps, Design meetups', 'Product designer who loves building things with local creators.'],
        ['liam@example.com', 'Liam O\'Brien', 'Hamilton, ON', 'Python, Data Analysis, Django', 'Open data, Cycling, Music', 'Backend developer looking for weekend collaboration projects.'],
        ['sofia@example.com', 'Sofia Chen', 'Hamilton, ON', 'Photography, Event Planning, Social Media', 'Community events, Photography', 'Event organizer and photographer connecting local creatives.']
    ];

    $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, full_name, location, skills, interests, biography, role, email_verified)
        VALUES (?,?,?,?,?,?,?, 'user', 1)");

    $userIds = [];

    foreach ($samples as $s) {
        $stmt->execute([
            $s[0],
            password_hash('User@123', PASSWORD_BCRYPT),
            $s[1],
            $s[2],
            $s[3],
            $s[4],
            $s[5]
        ]);

        $userIds[] = $pdo->lastInsertId();
    }

    $posts = [
        ['maya@example.com', 'Build a React community directory', 'Looking for a developer to help build a local community directory.', 'React, UI/UX Design', 'Toronto, ON'],
        ['liam@example.com', 'Weekend data project', 'Looking for someone interested in analyzing local open data.', 'Python, Data Analysis', 'Hamilton, ON'],
        ['sofia@example.com', 'Community photography event', 'Looking for volunteers and photographers for a local community event.', 'Photography, Event Planning', 'Hamilton, ON'],
        ['maya@example.com', 'Design a local meetup app', 'Looking for collaborators to design a simple app for local meetups.', 'Figma, UI/UX Design, React', 'Toronto, ON']
    ];

    $pStmt = $pdo->prepare("INSERT INTO posts (user_id, title, description, required_skills, location, created_at)
        VALUES (?, ?, ?, ?, ?, DATE_SUB(NOW(), INTERVAL ? DAY))");

    $day = 1;

    foreach ($posts as $p) {
        $ownerEmail = $p[0];

        if ($ownerEmail === 'maya@example.com') {
            $ownerId = $userIds[0];
        } elseif ($ownerEmail === 'liam@example.com') {
            $ownerId = $userIds[1];
        } else {
            $ownerId = $userIds[2];
        }

        $pStmt->execute([
            $ownerId,
            $p[1],
            $p[2],
            $p[3],
            $p[4],
            $day
        ]);

        $day++;
    }
}