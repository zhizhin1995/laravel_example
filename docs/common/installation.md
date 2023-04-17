[<< [Главная](./../../README.md) << [common](./index.md)

# Развертка проекта и полезная информация

## Необходимые и доп. модули PHP

php8.1 php8.1-opcache php8.1-dev php8.1-bcmath php8.1-ldap php8.1-pspell
php8.1-pdo php8.1-xml php8.1-mbstring php8.1-zip php8.1 php8.1-mysqlnd php8.1-pgsql
php8.1-dba php8.1-intl php8.1-apcu php8.1-common php8.1-fpm php8.1-odbc
php8.1-gd php8.1-imagick libapache2-mod-php8.1 php8.1-curl

## Команды при инициализации проекта

composer install - установка пакетов
php artisan jwt:secret - генерация jwt ключа
php artisan migrate - применение миграций

## Основные консольные команды по работе с миграциями

php artisan migrate - применение новых миграций (либо всех если БД пустая)
php artisan migrate:rollback --step X откат миграций (X - количество)
php artisan make:migration name создание новой миграции

## Консольные команды по работе с тестами

php artisan test - запуск всех тестов

*<b>Для работы следующих команд требуется php расширение xdebug</b>

XDEBUG_MODE=coverage php artisan test --coverage-html reports/ - отчет по покрытию тестами в виде html
XDEBUG_MODE=coverage php artisan test --coverage - отчет по покрытию тестами в консоли