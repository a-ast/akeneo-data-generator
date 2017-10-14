# Akeneo PHP API Client sandbox

Playing with the Akeneo PHP API client :rocket:

[![Build Status](https://travis-ci.org/nidup/akeneo-php-client-sandbox.png)](https://travis-ci.org/nidup/akeneo-php-client-sandbox)

## Requirements

## Docker Install

- [Docker Engine](https://docs.docker.com/engine/installation/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Local Install

Composer & PHP 7.1.

## Getting Started

Have a Akeneo PIM 2.0 installed.

## Install the Sandbox

## Docker Install

- Clone this repository and `cd` into it.
- Run `docker-compose pull`.
- Run `docker-compose up -d`.
- Run `bin/docker/composer.sh update --prefer-dist` to install the project's dependencies.

## Local Install

- Clone this repository and `cd` into it.
- Run `composer.phar update --prefer-dist` to install the project's dependencies.

## Configure the Sandbox

Connect to Akeneo PIM UI, then go to System > API Connections.

Create new credentials (client_id & secret).

Copy & paste app/parameters.yml.dist to app/parameters.yml.

Complete app/parameters.yml with the credentials.

## Use the Sandbox

- Run `bin/docker/console.sh` to run the `bin/console` script.

## Get First Product Data

- Run `bin/docker/console.sh akeneo:sandbox:get-first-product`

## Generate Attributes

- Run `bin/docker/console.sh akeneo:sandbox:generate-attributes 100`

## Generate Families

It generates families without label, attribute as image and attribute as label for now.

- Run `bin/docker/console.sh akeneo:sandbox:generate-family 100`

## Generate Complete Products

The generation is based on structure of the targeted PIM, it picks a random family and create a complete product.

- Run `bin/docker/console.sh akeneo:sandbox:generate-products 100 --with-images`

## Credits

Thanks @fzaninotto for Faker!

Thanks @matthiasnoback for the devtools docker images!