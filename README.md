## Installation Instructions
1. Clone project to your workspace;
2. Install dependencies:
(<i>* No external packages used </i>)

    ```
    $ composer install;
3. Setting up DB (Mysql) configs & run migrations: 
    ```
    $ php artisan migrate;
4. Run artisan command to start: 
    ```
   php artisan rss-parser:start OR php artisan schedule:work;
5. If previous steps was successfully, it will crawl given URL in every 5 minutes, output can be seen in main page of app;
   

## Тестовая задача : 
Необходимо разработать консольное приложение для парсинга и сохранения новостей в
локальную базу данных. Запуск команды должен осуществляться через cron.
## Парсинг:
Парсер должен обращаться к RSS странице новостей
http://static.feed.rbc.ru/rbc/logical/footer/news.rss. Каждая новость из ленты должна сохраняться в
локальную базу данных со следующим набором полей:
1. Название
2. Ссылка
3. Краткое описание
4. Дата и время публикации
5. Автор (если указан)
6. Изображение (если есть)

## Логирование:
Каждый запрос парсера должен логироваться в базу данных. Информация для логирования:
1. Дата и время
2. Request Method
3. Request URL
4. Response HTTP Code
5. Response Body

#### Минимальные требования:
1. Любой PHP фреймворк.
2. MySQL база данных.
#### Дополнительные плюсы можно заработать при реализации с:
1. Фреймворк Laravel8 или Symfony5.
2. База данных PostgreSQL.
3. Подключение административной панели.
4. Запуск парсинга осуществляется не по cron’у, а демоном.
