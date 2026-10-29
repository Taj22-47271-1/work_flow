# WorkTrack — Laravel + PostgreSQL Attendance Reports

Features based on `Test Task.pdf`:
- In-Out log report with employee search, date range, global search and per-column filters.
- Daily employee working-hours report with login/logout and working-hour calculation.
- Dashboard bar chart: one bar per day, default last 30 days, refreshes every 30 seconds.
- Responsive layout and print / Save PDF option.
- PostgreSQL configuration for pgAdmin.

## Requirements
PHP 8.2+, Composer, PostgreSQL 14+ and pgAdmin 4. Node/npm is not required for this starter.

## Windows setup
1. Extract this ZIP.
2. Open PowerShell in the extracted folder and run `.\setup.ps1`.
3. In pgAdmin, create a database named `attendance_db`.
4. Edit `attendance-system\.env` with your PostgreSQL password:
   ```
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=attendance_db
   DB_USERNAME=postgres
   DB_PASSWORD=YOUR_POSTGRES_PASSWORD
   ```
5. Run:
   ```
   cd attendance-system
   php artisan key:generate
   php artisan migrate
   php artisan serve
   ```
6. Visit http://127.0.0.1:8000

## Manual setup
Run `composer create-project laravel/laravel attendance-system`, then copy this ZIP's `laravel-app/app`, `laravel-app/routes`, `laravel-app/resources`, and `laravel-app/database/migrations` into the generated project (overwrite when asked). Configure `.env`, then run the commands above.

## Data import note
The provided `tbl_employee.sql` and attendance SQL ZIP are MariaDB/MySQL dumps, not PostgreSQL. They cannot be imported directly through pgAdmin. The app migrations create PostgreSQL-compatible schemas. For real data, export the source tables as CSV from MariaDB and import into the matching PostgreSQL tables using pgAdmin. See `database/import/README.md`.
The reports join `tbl_employee.id` to `tbl_attendance_log.emp_id`. Working hours use earliest IN and latest OUT per employee/day; if your workplace tracks multiple shifts or breaks, this pairing policy may need adjustment. `vendor/` is not bundled; Composer downloads dependencies during setup.
