symfony server:start

php -S localhost:8000 -t public

package
composer require api
composer require symfony/orm-packphp
composer require symfony/maker-bundle --dev
composer require symfony/validator symfony/serializer symfony/property-access
php bin/console cache:clear

bin/console make:entity
php bin/console make:migration
php bin/console doctrine:migrations:migrate

sync - created table php bin/console doctrine:migrations:sync-metadata-storage

migration fk key

// git@github.com:2001Ujjawal/symfony-curd-application.git

https://github.com/your-username/project-name.git

DTO

command 
    php bin/console make:class DTO/UserDTO
    php bin/console make:validator
