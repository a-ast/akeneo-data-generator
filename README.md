# Akeneo PHP API Client sandbox

Playing with the Akeneo PHP API client :rocket:

## Requirements

- [Docker Engine](https://docs.docker.com/engine/installation/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Getting Started

Have a Akeneo PIM 2.0 installed.

## Install the Sandbox

- Clone this repository and `cd` into it.
- Run `docker-compose pull`.
- Run `docker-compose up -d`.
- Run `bin/docker/composer.sh update --prefer-dist` to install the project's dependencies.

## Configure the Sandbox

Connect to Akeneo PIM UI, then go to System > API Connections.

Create new credentials (client_id & secret).

Copy & paste app/parameters.yml.dist to app/parameters.yml.

Complete app/parameters.yml with the credentials.

## Use the Sandbox

- Run `bin/docker/console.sh` to run the `bin/console` script.

## Get First Product Data

- Run `bin/docker/console.sh nidup:sandbox:get-first-product`

## Generate Products

- Run `bin/docker/console.sh nidup:sandbox:generate-products 100`

## Thanks

@matthiasnoback for the great devtools docker images ;)
