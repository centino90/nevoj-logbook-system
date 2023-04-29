# nevoj-logbook-system

## Prerequisite

### Download and Install Executables
xampp 3.3.0 
https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/8.1.12/xampp-windows-x64-8.1.12-0-VS16-installer.exe

composer 2.5.5
https://getcomposer.org/Composer-Setup.exe

node 18.16.0
https://nodejs.org/dist/v18.16.0/node-v18.16.0-x64.msi

### Setup database
- create database named nevoj-logbook-system

### Install php packages
To intall the php packages you need to run this command:
- `composer install`

### Install npm packages
To intall the npm packages you need to run this command:
- `npm install`

### Refresh App Key
To refresh app key you need to run this command:
- `php artisan key:generate`

### Migrate tables and seed
To migrate and seed you need to run this command:
- `php artisan migrate:fresh --seed`

### Start app
To start the app you need to run this command:
- `./start.sh`


## TODO

### Initial
- Layout
  - Transactions stats
  - Professor stats
  - Course stats
  - Course chart
  - Professor table

- Content
  - Transaction stats
    - Total transaction
    - Total served transaction
    - Total unserved transaction
  - Professor stats
    - Total professors
    - 
  - Course stats
  - Course chart
  - Professor table
- Create a redirect button in 404, 405, 401, 500 error page
- Add Delete/Restore action in transaction edit page //

### Revisions
- finish sorting algo // 
- finish dashboard page //
- finish dummy data seeder //
- update login page transaction link to "Log" //
