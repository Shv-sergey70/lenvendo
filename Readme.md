Запуск проекта:

1. Установите php-зависимости: composer install;
2. Установите js-зависимости: npm install;
3. Выполните: docker-compose up, для создания БД;
4. Выполните миграции: php bin/console doctrine:migrations:migrate;
5. Создайте фикстуры: php bin/console doctrine:fixtures:load;
6. Поднимите сервер командой: symfony serve;
