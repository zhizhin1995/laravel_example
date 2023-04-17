[<< [Главная](./../../README.md)

## Полезная информация
* [installation](./installation.md) - инструкция по установке
* [tech](./tech.md) - тех. требования


Rabbit настройки

rabbitmqctl eval 'application:set_env(rabbit, consumer_timeout, 36000000).' // таймаут консьюмеров 10 часов
