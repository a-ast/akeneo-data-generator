# Akeneo PHP API Client sandbox

Playing with the Akeneo PHP API client :rocket:

## Requirements

- [Docker Engine](https://docs.docker.com/engine/installation/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Getting started

Have a Akeneo PIM 1.8 installed.

## Install this repository

- Clone this repository and `cd` into it.
- Run `docker-compose pull`.
- Run `docker-compose up -d`.
- Run `bin/docker/composer.sh update --prefer-dist` to install the project's dependencies.

## Generate a web api token from your PIM

Connect to your Akeneo PIM UI, then go to System > API Connections.

Create new credentials (client_id & secret).

## Configure the sandbox

Copy / paste app/parameters.yml.dist to app/parameters.yml.

Complete app/parameters.yml with the credentials.

## Use the web api sandbox

- Run `bin/docker/console.sh` to run the `bin/console` script.

## Get first product data

- Run `bin/docker/console.sh nidup:sandbox:get-first-product`

## Import generated products

- Run `bin/docker/console.sh nidup:sandbox:generate-products 100`

## Thanks

@matthiasnoback for the great devtools docker images ;)
