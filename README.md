# OpenWRT-API-Project

Projeto de graduação, emulação do OpenWRT para simulação de comunicação com API Zero Touch.

##Getting Started

###Dependências

* `sudo apt-get update && sudo apt-get install -y qemu-system-x86`
* [Docker](https://docs.docker.com/engine/install/ubuntu/)
* [Docker-compose](https://docs.docker.com/compose/install/)

###Instalação

* Na pasta mysql_env execute: `docker-compose up -d`.
* Execute `sh setup.sh` e em seguida `sh qemu-up.sh` na pasta raiz do projeto.
