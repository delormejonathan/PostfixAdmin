curl -sS https://getcomposer.org/installer | php
php composer.phar install
php app/console doctrine:database:create --env=prod
php app/console doctrine:schema:update --force --env=prod
php app/console doctrine:fixtures:load --env=prod --append

chmod 777 app/cache
chmod 777 app/logs

php app/console router:dump-apache -e=prod --no-debug >> web/.htaccess
php app/console fos:js-routing:dump

rm -rf app/cache/*
rm -rf app/logs/*