# Changelog

Contain the only changes that need to be done manually.

## Manual Database Changes (Breaking changes)

Starting after commit [9596ce3db1b40fcffac1ccb813ff2f5e9dfc217e](https://github.com/rayusamBoy/Laravel_tenancy_sms/commit/9596ce3db1b40fcffac1ccb813ff2f5e9dfc217e), the database schema has undergone breaking changes. If you cloned or downloaded the repository before this commit, you will need to manually apply these changes to all of your existing databases, including both tenancy and non-tenancy scopes.

Key Points:

* Affects only users who cloned the repository before this very commit.
* Does not affect new clones or downloads after this commit; they will automatically have the updated schema.
* Please ensure you update both tenancy and non-tenancy databases.

The default connection used is mysql.

Please follow the instructions in the relevant migration or schema update files to ensure your databases are up-to-date. If you need assistance with applying these changes, feel free to reach out.

1. **Modify `is_notifiable` column of `users` table for both scopes:**

   *Migrations:*

   ```bash
   php artisan migrate --path=database\migrations\changes\2025_01_07_174316_modify_is_notifiable_column_migration.php
   ```

   ```bash
   php artisan tenants:migrate --path=database\migrations\changes\2025_01_07_174316_modify_is_notifiable_column_migration.php
   ```

   *SQL:*

   ```sql
   ALTER TABLE `users` CHANGE `is_notifiable` `is_notifiable` TEXT NULL;
   ```

2. **Add records to `settings` table for both scopes:**

   *Seeds:*

   ```bash
   php artisan db:seed --class=BreakingChangeInsertNewRecordsToSettingsTable
   ```

   ```bash
   php artisan tenants:seed --class=BreakingChangeInsertNewRecordsToSettingsTable
   ```

   *SQL:*

   ```sql
    INSERT INTO `settings` (`id`, `type`, `description`, `created_at`, `updated_at`) VALUES (NULL, 'enable_email_notification', '1', NULL, '2024-12-31 11:11:00'),(NULL, 'enable_sms_notification', '1', NULL, '2024-12-31 11:11:00'),(NULL, 'enable_push_notification', '1', NULL, '2024-12-31 11:11:00');
   ```

3. **Add `hidden_alert_ids` column to `users` table for both scopes:**

   *Migrations:*

   ```bash
   php artisan migrate --path=database\migrations\changes\2025_01_23_150530_add_hidden_alert_ids_to_users_table.php
   ```

   ```bash
   php artisan tenants:migrate --path=database\migrations\changes\2025_01_23_150530_add_hidden_alert_ids_to_users_table.php
   ```

   *SQL:*

   For MySQL/PostgreSQL:

   ```sql
   ALTER TABLE `table_name` ADD COLUMN `hidden_alert_ids` JSON DEFAULT NULL;
   ```

   For other databases (fallback to TEXT):

   ```sql
   ALTER TABLE `table_name` ADD COLUMN `hidden_alert_ids` TEXT DEFAULT NULL;
   ```

## Manual Database Changes (Non-Breaking changes)

1. **Drop the unique index on the `name` column of the `users` table to prevent potential overhead during data insertion, as multiple users can have similar names.**

   *Migrations:*

   ```bash
   php artisan migrate --path=database\migrations\changes\2025_01_13_151412_drop_name_index_from_users_table.php
   ```

   ```bash
   php artisan tenants:migrate --path=database\migrations\changes\2025_01_13_151412_drop_name_index_from_users_table.php
   ```

   *SQL:*

   ```sql
   ALTER TABLE `users` DROP INDEX `name`;;
   ```
