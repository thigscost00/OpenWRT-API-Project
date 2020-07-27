## **0.2v**
* Configurações podem estar dentro de pastas, só deve haver um config.json
* Configurações são comprimidas e passadas como tar.gz pela API
* Criados métodos na classe ZeroTouch para lidar com todas requisições
* Método gettoken agora salva e compara corretamente o tempo de expiração do token


## **0.1v**
* Página principal para registro dos macs
* Campo na tabela de roteador, especificando arquivo de configuração
* Script python itera de forma generalizada arquivos json com configuração UCI
* Rota nova na API para refresh token
* View com listagem de roteadores cadastrados
* Adição de Bootstrap
* Adição do pacote ui
* Config json para comandos shell
* remoção do arquivo public/.htaccess (necessário somente para apache)
* Arquivos de configuração guardados em storage agora
* Configuração para Guest wifi adicionada
* Instalação de node e npm no container de php
* Modularização do script Python para OOP
* Rota register retorna view de listagem dos Macs, ao inves do token
* Nova lógica de implementação das configurações.
