-- Grant privileges for tenant database creation
-- The app needs to create databases dynamically for each tenant
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
