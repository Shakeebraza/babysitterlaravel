# Babysitter App Backend

## Technology Used

### Admin/API (Laravel)
- **Framework:** Laravel (Open-source PHP web framework)

## Prerequisites
Ensure that your server meets the requirements for running Laravel. You'll need PHP, Composer, a web server (Apache or Nginx), and a database server (MySQL, PostgreSQL, SQLite, or SQL Server).
- PHP > 8
- composer

## Installing the Project

To set up and run the project, follow these steps after completing all installations:

1. Place your project folder in the desired location.
2. Open a terminal or command prompt.
3. Navigate to your project directory. You should find files like `composer.json`.
4. **Install Dependencies:** Run `composer install` to install the necessary dependencies for your project.
5. Configure your .env file on the server. Make sure you have the correct database credentials, cache settings, and other environment-specific configurations.
6. **Generate Application Key:** Run `php artisan key:generate` to generate a unique application key for your Laravel application. This key is used for encryption and should be kept secure.
7. **Directory Permissions:** Ensure that the storage and bootstrap/cache directories are writable by the web server. You can set permissions using chmod command.
8. Configure your web server to serve your Laravel application. Set up a virtual host pointing to the public directory of your Laravel project.
9. **Database Setup:** Create a new database for your Laravel application on your database server.
10. Update the .env file with the appropriate database connection details.
11. **Database Migrations:** Run database migrations using the `php artisan migrate` command. This will create the necessary tables in your database based on your migration files.
12. **Set Up Cron Jobs (Optional):** If your application requires scheduled tasks, set up a cron job to run the Laravel scheduler. Add the following Cron entry to your server: `* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1`.
13. **Optimization (Optional):** Run `php artisan optimize` to optimize your application's performance by pre-compiling classes and routes.
