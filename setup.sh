#! /bin/bash

PHPCONTAINER="php-fpm"
USER=$(cat mysql_env/docker-compose.yml | grep MYSQL_USER | awk -F"=" '{print $2}')
DATABASE=$(cat mysql_env/docker-compose.yml | grep MYSQL_DATABASE | awk -F"=" '{print $2}')
PASSWORD=$(cat mysql_env/docker-compose.yml | grep MYSQL_PASSWORD | awk -F"=" '{print $2}')

#Modificar o .env do Laravel
(cd mysql_env/server && cp .env.example .env &&
    sed -i 's/DB_HOST=.*/DB_HOST=mysql/g' .env &&
    sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DATABASE/g" .env &&
    sed -i "s/DB_USERNAME=.*/DB_USERNAME=$USER/g" .env &&
    sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$PASSWORD/g" .env
)

#Gerar chave de criptografia de sessão do usuário
docker exec -d $PHPCONTAINER php artisan key:generate

#Baixar vendors
docker exec -d $PHPCONTAINER composer update

#Criar tabela de roteadores no banco
docker exec -d $PHPCONTAINER php artisan migrate

#Adicionar secret key para jwt-auth
docker exec -d $PHPCONTAINER php artisan jwt:secret

#Mudar permissões dos arquivos Bootstrap e storage
docker exec -d $PHPCONTAINER sh init.sh
rm mysql_env/server/init.sh 
