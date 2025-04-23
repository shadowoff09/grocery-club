# Projeto AINet

## Initial Setup


1. Copy the `.env.example` file to `.env` and configure your environment variables:
```bash
cp .env.example .env
```

2. Set up your database connection in the `.env` file. Make sure to update the following variables:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=projeto_ainet
DB_USERNAME=root
DB_PASSWORD=
```

3. Install the dependencies using Composer:
```bash
composer install
```

4. Generate the application key:
```bash
# Only run this if in the file .env the APP_KEY is empty
php artisan key:generate
```
5. Check if the application can connect to the database:
```bash
php artisan db:check
```

6. Run the migrations to set up the database:
```bash
# The DB must be created before running this command
php artisan migrate:fresh
```

7. Seed the database with initial data:
```bash
# Tip: Disable real-time protection on Windows Defender to speed up the process
php artisan db:seed
```

8. Install the Frontend dependencies:
```bash
npm install
```

9. Build the assets:
```bash
npm run build
```

10. Run the application:
```bash
composer run dev
```

11. Open your browser and navigate to `http://localhost:8000` to access the application.

## Example Credentials

Email: b1@mail.pt\
Password: 123
