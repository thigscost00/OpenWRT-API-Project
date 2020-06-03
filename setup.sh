#! /bin/bash

PHPCONTAINER="php-fpm"
USER=$(cat mysql_env/docker-compose.yml | grep MYSQL_USER | awk -F"=" '{print $2}')
DATABASE=$(cat mysql_env/docker-compose.yml | grep MYSQL_DATABASE | awk -F"=" '{print $2}')
PASSWORD=$(cat mysql_env/docker-compose.yml | grep MYSQL_PASSWORD | awk -F"=" '{print $2}')

# Modificar o .env do Laravel
(cd mysql_env/server && cp .env.example .env &&
    sed -i 's/DB_HOST=.*/DB_HOST=mysql/g' .env &&
    sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DATABASE/g" .env &&
    sed -i "s/DB_USERNAME=.*/DB_USERNAME=$USER/g" .env &&
    sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$PASSWORD/g" .env
)

# Baixar vendors
docker exec $PHPCONTAINER composer install

# Gerar chave de criptografia de sessão do usuário
docker exec -d $PHPCONTAINER php artisan key:generate

# Adicionar secret key para jwt-auth
docker exec -d $PHPCONTAINER php artisan jwt:secret

# Criar tabela de roteadores no banco
docker exec -d $PHPCONTAINER php artisan migrate

# Preencher a tabela 'configurations' com arquivos de configuração iniciais
docker exec -d $PHPCONTAINER php artisan db:seed

# Baixa dependências Node
# docker exec -d $PHPCONTAINER npm install
# docker exec -d $PHPCONTAINER npm run prod

# Mudar permissões dos arquivos Bootstrap e storage
docker exec -d $PHPCONTAINER sh init.sh
rm mysql_env/server/init.sh
