# PHP 7.0+ MySQL Session Handler

This repository contains a custom PHP session handler using MySQL as a backend. 

## How to Install

#### Using [Composer](http://getcomposer.org/)

Install composer if you haven't already ([Linux instructions](http://blog.programster.org/ubuntu-install-composer)).

Navigate to your project's directory and run the following command:
    
```bash
composer require "programster/mysql-session-handler"
```

## How to use

```sql
CREATE TABLE IF NOT EXISTS `my_sessions_table` (
    `id` varchar(32) NOT NULL,
    `timestamp` int(10) unsigned DEFAULT NULL,
    `data` mediumtext,
    PRIMARY KEY (`id`),
    KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

```

```sh
require 'vendor/autoload.php';

$db = new mysqli('localhost', 'username', 'password', 'database');
$handler = new \Programster\SessionHandler\SessionHandler($db, 'my_sessions_table');
session_set_save_handler($handler, true);
session_start();
$_SESSION['my_session_variable'] = 'some data here';

```

## Authors

* [Programster](https://github.com/Programster)
* [Jamie Cressey](https://github.com/JamieCressey)

## License

MIT Public License
