KLENSE
======

See it in action: http://klense.altervista.org


Requirements
------------

* PHP >= 5.2
* MySQL >= 5.1

Getting started
---------------

1. Extract the source code
2. Setup the DB
    1. Run the queries located in `db/database.sql`
3. Set configuration
    1. Create a `config.override.php` file in `src/public/`
    2. Copy the lines you want to change from `config.php` and paste them in `config.override.php`
4. Build language files
    1. Execute the bash script located in `build/buildlocales.sh`. This will generate some *.mo files inside `src/public/content/locales/*/LC_MESSAGES/`
5. Run it! The entry point is `src/public/index.php`
