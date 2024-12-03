PHP MySQL Course Registration Application

This is a school project.

Description

This PHP and MySQL web application manages user authentication and course enrollment. It features user registration with email verification, secure login/logout, password reset, and profile management. Users can view and register for courses, upload courses via CSV files, and manage their enrollments. The project uses Composer for dependencies and is containerized with Docker and Docker Compose for deployment.

Installation and Setup

Prerequisites

Docker and Docker Compose installed on your machine.
Composer installed globally.
Git installed on your machine.
Steps

Clone the Repository git clone https://github.com/yourusername/your-repo.git

Navigate to the Project Directory cd your-repo

Install PHP Dependencies composer install

Copy the Example Environment File cp .env.example .env

Configure Environment Variables Open the .env file and update the values with your own credentials.

Build and Run the Docker Containers docker-compose up --build

Access the Application

Web Application: http://localhost:8080
phpMyAdmin: http://localhost:8081
Initialize the Database (If Necessary)

The init.sql script will automatically run when the database container is first created.

If you need to reinitialize, you can remove the db_data volume:

docker-compose down -v docker-compose up --build

Additional Configuration

Edit the file named .env.example with the following content:

Database configuration
DB_HOST=your_database_host DB_USER=your_database_user DB_PASSWORD=your_database_password DB_NAME=your_database_name

MySQL root password
MYSQL_ROOT_PASSWORD=your_mysql_root_password

MySQL database name
MYSQL_DATABASE=your_database_name

Database user (same as DB_USER)
MYSQL_USER=your_database_user MYSQL_PASSWORD=your_database_password

phpMyAdmin configuration
PMA_HOST=db

Ensure you rename .env.example to '.env'.

Update db_connection.php

Modify your db_connection.php to use environment variables instead of hard-coded credentials.

Updated db_connection.php:

load(); // Database connection settings using environment variables $db_host = getenv('DB_HOST'); $db_user = getenv('DB_USER'); $db_password = getenv('DB_PASSWORD'); $db_name = getenv('DB_NAME'); try { $conn = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password); // Set PDO attributes $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exceptions $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Use real prepared statements } catch (PDOException $e) { error_log("Database connection failed: " . $e->getMessage()); echo "An error occurred while connecting to the database."; } ?>
Contributing

Contributions are welcome! Please open an issue or submit a pull request for improvements or bug fixes.
