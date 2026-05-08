FROM mariadb:lts-ubi

COPY dbstore.sql /docker-entrypoint-initdb.d/dbstore.sql
COPY custom.cnf /etc/mysql/conf.d/custom.cnf