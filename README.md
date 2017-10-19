# Akeneo PHP API Client sandbox

Akeneo PHP API client utils :rocket:

[![Build Status](https://travis-ci.org/nidup/akeneo-php-client-sandbox.png)](https://travis-ci.org/nidup/akeneo-php-client-sandbox)

## Requirements

## Docker Install

- [Docker Engine](https://docs.docker.com/engine/installation/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Local Install

Composer & PHP 7.1.

## Getting Started

Last Akeneo PIM 2.0.x installed.

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

## Generate Category Tree

For instance, to generate 1 category tree with 99 children categories on 3 levels.

- Run `bin/docker/console.sh akeneo:sandbox:generate-category-trees 1 100 3`

## Generate Attributes

For instance, to generate 100 attributes.

- Run `bin/docker/console.sh akeneo:sandbox:generate-attributes 100 --useable-in-grid`

## Generate Families

For instance, to generate 100 families.

- Run `bin/docker/console.sh akeneo:sandbox:generate-family 100`

## Generate Complete Products

The generation is based on structure of the targeted PIM, it picks a random family and creates a product filling all values.

- Run `bin/docker/console.sh akeneo:sandbox:generate-products 100 --with-images`

## Credits

Thanks @fzaninotto for Faker!

Thanks @matthiasnoback for the devtools docker images!