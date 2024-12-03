# PHP MySQL Course Registration Application
This is a school project.

## Description

This PHP and MySQL web application manages user authentication and course enrollment. It features user registration with email verification, secure login/logout, password reset, and profile management. Users can view and register for courses, upload courses via CSV files, and manage their enrollments. The project uses Composer for dependencies and is containerized with Docker and Docker Compose for deployment.

## Installation and Setup

### Prerequisites

- Docker and Docker Compose installed on your machine.
- Composer installed globally.
- Git installed on your machine.

### Steps

1. **Clone the Repository**

   ```bash
   git clone https://github.com/yourusername/your-repo.git

2. Create an .env.example File
Provide an example environment file with placeholder values for others to configure their own environment variables.

Create a file named .env.example with the following content:

env
Copy code
# Database configuration
DB_HOST=your_database_host
DB_USER=your_database_user
DB_PASSWORD=your_database_password
DB_NAME=your_database_name

# MySQL root password
MYSQL_ROOT_PASSWORD=your_mysql_root_password

# Database user (same as DB_USER)
MYSQL_USER=your_database_user
MYSQL_PASSWORD=your_database_password

# phpMyAdmin configuration
PMA_HOST=db
3. Update db_connection.php
Modify your db_connection.php to use environment variables instead of hard-coded credentials.

Updated db_connection.php:

php
Copy code
<?php
// Use absolute path to avoid issues with relative paths
require_once __DIR__ . '/vendor/autoload.php';  // Autoload Composer dependencies

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database connection settings using environment variables
$db_host = getenv('DB_HOST');
$db_user = getenv('DB_USER');
$db_password = getenv('DB_PASSWORD');
$db_name = getenv('DB_NAME');

try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    // Set PDO attributes
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      // Enable exceptions
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);              // Use real prepared statements
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    echo "An error occurred while connecting to the database.";
}
?>
Notes:

Remove Sensitive Files from Git Tracking:
If db_connection.php was previously committed with sensitive data, remove it from your Git history using tools like BFG Repo-Cleaner.
Add db_connection.php to .gitignore:
Ensure that db_connection.php is listed in your .gitignore file to prevent it from being tracked.
4. Update docker-compose.yml
Modify your docker-compose.yml file to use environment variables and remove hard-coded sensitive data.

Updated docker-compose.yml:

yaml
Copy code
version: '3.8'

services:
  web:
    build: .
    ports:
      - "8080:80"
    volumes:
      - .:/var/www/html
    depends_on:
      - db
    env_file:
      - .env
    restart: unless-stopped

  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: ${MYSQL_ROOT_PASSWORD}
      MYSQL_DATABASE: ${MYSQL_DATABASE}
      MYSQL_USER: ${MYSQL_USER}
      MYSQL_PASSWORD: ${MYSQL_PASSWORD}
    volumes:
      - db_data:/var/lib/mysql
      - ./init.sql:/docker-entrypoint-initdb.d/init.sql
    restart: unless-stopped

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    ports:
      - "8081:80"
    environment:
      PMA_HOST: ${PMA_HOST}
      PMA_USER: ${MYSQL_USER}
      PMA_PASSWORD: ${MYSQL_PASSWORD}
    depends_on:
      - db
    env_file:
      - .env
    restart: unless-stopped

volumes:
  db_data:
Notes:

Use ${VARIABLE_NAME} Syntax:
Docker Compose will replace ${VARIABLE_NAME} with the value from your .env file or environment variables.
Ensure .env File is Excluded:
Confirm that your .env file is listed in your .gitignore to prevent sensitive data from being committed.
5. Update Your README.md
Provide clear setup instructions and information about your project.

Sample README.md:

markdown
Copy code
# PHP MySQL Course Registration Application

## Description

This PHP and MySQL web application manages user authentication and course enrollment. It features user registration with email verification, secure login/logout, password reset, and profile management. Users can view and register for courses, upload courses via CSV files, and manage their enrollments. The project uses Composer for dependencies and is containerized with Docker and Docker Compose for deployment.

## Installation and Setup

### Prerequisites

- Docker and Docker Compose installed on your machine.
- Composer installed globally.
- Git installed on your machine.

### Steps

1. **Clone the Repository**

   ```bash
   git clone https://github.com/yourusername/your-repo.git

Navigate to the Project Directory:
cd your-repo

Install PHP Dependencies:
composer install

Copy the Example Environment File:
cp .env.example .env

Configure Environment Variables:
Open the .env file and update the values with your own credentials.

Build and Run the Docker Containers:
docker-compose up --build

Access the Application
    Web Application: http://localhost:8080
    phpMyAdmin: http://localhost:8081

Initialize the Database (If Necessary)
    The init.sql script will automatically run when the database container is first created.

    If you need to reinitialize, you can remove the db_data volume:
        docker-compose down -v
        docker-compose up --build

Notes
Do Not Use Default Credentials in Production:
    Always replace default passwords with strong, unique passwords.
Ensure Sensitive Files Are Not Committed:
    Verify that .env and other sensitive files are excluded from the repository.
Database Migrations:
    Use a migration tool for database schema changes in a production environment.

Contributing
Contributions are welcome! Please open an issue or submit a pull request for improvements or bug fixes.