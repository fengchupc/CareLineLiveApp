# CareLineLiveApp

### db schema tables relationship:
- Carers and shifts: one-to-many (one Carer can have multiple shifts)
- Clients and shifts: one-to-many (one client can have multiple shifts)
- Shifts and Carers: many-to-one (one shift belongs to one Carer)
- Shifts and Clients: many-to-one (one shift belongs to one client)

## Assumptions
- Backend uses Laravel 
- Frontend is a static AngularJS 1.x app, served via PHP's built-in server (no Node.js required)
- Default DB is SQLite (file: `database/database.sqlite`)
- API endpoints:
  - `/carers` (CRUD, paginated)
  - `/clients` (CRUD, paginated)
  - `/shifts` (CRUD, paginated, with overlap validation)
- Pagination: default 10 per page, structure `{ data: [...], pagination: {...} }`

# CareLineLive Home Care Scheduler

## Setup Instructions

### Backend (Laravel)
1. **Install PHP dependencies:**
   ```sh
   composer install
   ```
2. **Copy environment file and set up DB:**
   ```sh
   cp .env.example .env
   # Edit .env as needed
   touch database/database.sqlite
   ```
3. **Generate app key:**
   ```sh
   php artisan key:generate
   ```
4. **Run migrations and seeders to create the database table and sample data:**
   ```sh
   php artisan migrate --seed
   ```
5. **Start backend server:**
   ```sh
   php artisan serve --host=127.0.0.1 --port=8005
   ```
   The API will be available at http://127.0.0.1:8005

### Frontend (AngularJS 1.x)
1. **No build step required.**
2. **Start the PHP static server for the frontend:**
   ```sh
   php -S localhost:8080 -t frontend frontend/serve.php
   ```
   The frontend will be available at http://localhost:8080

### Running Tests
- **Feature & unit tests:**
  ```sh
  php artisan test
  ```

