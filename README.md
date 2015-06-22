api
=======

# Установка

git clone git@github.com:tripang/api.git

cd api

надо поменять параметры базы MySQL:

nano app/config/parameters.yml

далее

php app/console doctrine:database:create

php app/console doctrine:schema:update --force

php app/console server:run

Сайт должен быть доступен по адресу

http://localhost:8000/
