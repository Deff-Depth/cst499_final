PHP MySQL Course Registration Application

Description:
    A PHP and MySQL web application for user authentication and course management. 
    Features include:
        User registration with email verification.
        Secure login/logout, password reset, and profile management.
        Course enrollment, waitlist functionality, and CSV-based course uploads.
        Containerized with Docker and Docker Compose, with Composer for dependency management.

Installation and Setup
  Prerequisites:
    Docker and Docker Compose installed.
    Composer installed globally.
    Git installed.
Steps
    Clone the Repository: 
        git clone https://github.com/Deff-Depth/cst499_final
        cd your-repo

    Install PHP Dependencies
        composer install
    
    Set Up Environment Variables
        Copy the example .env file and configure it:
            cp .env.example .env

    Edit .env with your database credentials:
        DB_HOST=localhost
        DB_USER=root
        DB_PASSWORD=password
        DB_NAME=course_registration
        MYSQL_ROOT_PASSWORD=root_password
        PMA_HOST=db

    Build and Start Containers:
        docker-compose up --build
    
    Access the Application
        Web Application: http://localhost:8080
        phpMyAdmin: http://localhost:8081

    Initialize the Database
        The init.sql script initializes the database automatically on the first run.
        To reset the database:
            docker-compose down -v
            docker-compose up --build
Notes

    Sensitive Data: Ensure .env and sensitive files are listed in .gitignore to prevent them from being committed.
    Security: Replace default credentials with strong, unique passwords in production.
    Database Migrations: Use a migration tool for schema changes in production environments.

Contributing
    Contributions are welcome! Submit an issue or open a pull request for improvements or bug fixes.
