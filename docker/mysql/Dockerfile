FROM mysql:5.7

LABEL maintainer="shv.sergey70@gmail.com"

ENV MYSQL_ALLOW_EMPTY_PASSWORD=1

COPY ./init.sql /docker-entrypoint-initdb.d/

EXPOSE 3306 3307


# docker container run --name=mysql -p=3306:3306 app_symfony/mysql
# docker build -t app_symfony/mysql .

# docker container exec -it mysql mysql -usymfony -psymfony
