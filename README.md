kknyga-web
============
composer install && npm i && gulp

bin/console doctrine:database:create
bin/console doctrine:schema:update -f 

my.cnf
[mysqld] sql-mode=""
