# Changelog

Contain the only changes that need to be done manually.

## Manual Database Change (Breaking change)

Starting after commit [1184327b2c548542f3ae2f37d81666aca526fe17](https://github.com/rayusamBoy/Laravel_tenancy_sms/commit/1184327b2c548542f3ae2f37d81666aca526fe17), the database schema has undergone breaking change. If you cloned or downloaded the repository before this commit, you will need to manually apply this change to all of your existing non-tenancy databases.

Key Points:

* Affects only users who cloned the repository before this very commit.
* Does not affect new clones or downloads after this commit; they will automatically have the updated schema.
* Please ensure you update non-tenancy databases only.

The default connection is MySQL on the Windows platform.

Please follow the instructions in the relevant migration or schema update files to ensure your database is up-to-date. If you need assistance with applying this change, feel free to reach out.

1. **Drop `no_of_periods` column from `staff_records` table for non-tenancy scope:**

   *Migration:*

   ```bash
   php artisan migrate --path=database/migrations/changes/2025_03_04_214226_drop_no_of_periods_column_from_staff_records_table.php
   ```

   *SQL:*

   ```sql
    ALTER TABLE staff_records DROP COLUMN no_of_periods;
   ```
