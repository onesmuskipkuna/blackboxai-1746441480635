# Manual Test Instructions for Gym Supervision System

## Prerequisites
- Ensure the MySQL database `gym_supervision` is set up with the schema from `database/schema.sql`.
- Ensure the web server is configured to serve the `gym-supervision-system/public` directory.
- Ensure PHP and required extensions are installed.

## 1. Login and Logout
- Navigate to `login.php`.
- Enter valid user credentials and submit.
- Verify successful login redirects to the dashboard (`index.php`).
- Click logout link and verify redirection to login page.

## 2. Dashboard (`index.php`)
- Verify charts for Trainer Ratings, Cleaner Ratings, and Machine Status are displayed.
- Verify data in charts matches database content.

## 3. Gym Areas (`gym_areas.php`)
- Verify list of gym areas is displayed.
- Add, edit, or delete gym areas if functionality exists.
- Verify changes reflect in the database.

## 4. Cleaners (`cleaners.php`)
- Verify list of cleaners is displayed.
- Add, edit, or delete cleaners if functionality exists.
- Verify changes reflect in the database.

## 5. Maintenance (`maintenance.php`)
- Verify list of machines and their statuses.
- Update machine status and add remarks.
- Verify notifications are sent (check logs or email).
- Verify status updates reflect in the database.

## 6. Maintenance Status History (`maintenance_status_history.php`)
- Select a machine and view its maintenance status history.
- Verify history entries match database records.

## 7. Classes and Trainers (if UI exists)
- Verify class creation, trainer assignment, attendance marking.
- Verify trainer ratings can be added and viewed.

## Additional Notes
- Check for proper error handling and validation on all forms.
- Verify responsive design and accessibility.
- Test with different user roles if applicable (admin, supervisor, maintenance, trainer).

---

Perform these manual tests to ensure the system functions as expected. For automated testing, consider using PHPUnit or similar frameworks to create unit and integration tests for the PHP classes.
