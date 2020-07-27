# OpenWRT-API-Project

Projeto de graduação, emulação do OpenWRT para simulação de comunicação com API Zero Touch.

## Getting Started

### Dependências

* `sudo apt-get update && sudo apt-get install -y qemu-system-x86`
* [Docker](https://docs.docker.com/engine/install/ubuntu/)
* [Docker-compose](https://docs.docker.com/compose/install/)

### Instalação

* Na pasta mysql_env execute: `docker-compose up -d`.
* Execute `sh setup.sh` e em seguida `sudo ./qemu-up.sh` na pasta raiz do projeto.

## TODO:

- [ ] Melhorar página de Registro
- [ ] Criar página de Update do Roteador
- [ ] Criar rota da API para verificação da necessidade de configuração
- [ ] Criar rota da API para recebimento de logs
- [ ] Criar página para criação de configuração
- [x] Rota para refresh do token
- [x] Verificação se mac já está cadastrado
- [x] Exibição de home page
- [x] Modularização do script pela adição de mais funções na classe ZeroTouch
