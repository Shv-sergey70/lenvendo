Запуск проекта:

1. Склонируйте репозиторий.
2. Установите php-зависимости: **composer install**;
3. Установите js-зависимости: **npm install**;
4. Выполните: **docker-compose up**, для запуска mysql;
5. Для создания БД выполните: **php bin/console doctrine:database:create**;
6. Выполните миграции: **php bin/console doctrine:migrations:migrate**;
7. Создайте фикстуры: **php bin/console doctrine:fixtures:load**;
8. Соберите build: **npm run build**;
9. Поднимите сервер командой: **symfony serve**;
