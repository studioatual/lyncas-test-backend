# Teste para Dev PHP

[![Lyncas Logo](https://img-dev.feedback.house/TCo5z9DrSyX0EQoakV8sJkx1mSg=/fit-in/300x300/smart/https://s3.amazonaws.com/feedbackhouse-media-development/modules%2Fcore%2Fcompany%2F5c9e1b01c5f3d0003c5fa53b%2Flogo%2F5c9ec4f869d1cb003cb7996d)](https://www.lyncas.net)
### Requisitos

- Google books (https://developers.google.com/books/)

## Detalhes da prova

### O que foi desenvolvido (Backend)

- Um Projeto do zero utilizando micro framework (Slim Framework 4) em padrão MVC.
- Consulta a api do google books com um serviço em (CURL)
- Criação de Teste Unitário (básico) para a camada Repository
- Paginação e Modelagem dos dados.
- Criação de Exception para quando o parametro da consulta não for enviado.

### Instruções para Instalação do Backend

Arquivo .env

- <b>Renomear o arquivo .env-example para .env</b>

Rodar o docker compose

- <b>docker-compose up -d</b>

Instalar os pacotes do php (dependencias)

- <b>docker exec -it phpserver composer install</b>

Acessar a api pela url <b>http://localhost</b>

Run PHPUNIT

- <b>docker exec -it phpserver vendor/bin/phpunit</b>

Criar alias ( test )

- <b>docker exec -it phpserver alias test='vendor/bin/phpunit'</b>
- <b>docker exec -it phpserver test</b>

---
