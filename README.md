# Демонстрационный проект(CRUD)
Это CRUD на laravel 8.80.0 + php 7.4.5 + MariaDB 10.3, для корректной работы на 
машине должны быть установлены соответствующие технологии + composer

## Как развернуть проект
1) Клонировать репозиторий git clone https://github.com/alexLobanov12/demo.git
2) После этого перейти в корень проекта и запутить команду composer install
3) Переименовать файл env.example в .env, зайти в него и заполнить параметры с 
префиксом DB_ своими значениями
4) Запустить в консоли команды php artisan migrate и php artisan db:seed