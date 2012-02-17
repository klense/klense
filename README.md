KLENSE
======

See it in action: http://klense.altervista.org


Requirements
------------

* PHP >= 5.2
* MySQL >= 5.1

Getting started
---------------

1. Extract the source code in your web space
2. Setup the DB
    1. Run the queries located in `db/database.sql`
3. Set configuration
    1. Create a `config.override.php` file in `src/public/`
    2. Copy the lines you want to change from `config.php` and paste them in `config.override.php`
4. Build files
    1. Execute the `build/build_auto.sh` bash script.
        * When asked for the `index.php` directory:
            * If `index.php` will be accessible from `http://www.example.com/index.php`, insert `/`
            * If `index.php` will be accessible from `http://www.example.com/my/path/index.php`, insert `/my/path/`
5. Set permissions
    * klense needs write access to:
        * `src/public/content/uploads/`
        * `src/public/content/uploads-temp/`
        * `src/public/includes/libs/smarty/templates_c/`
6. Run it! The entry point is `src/public/index.php`
