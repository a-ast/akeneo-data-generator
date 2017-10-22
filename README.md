# Akeneo Data Generator

Command line tool which generates and injects data to an Akeneo PIM instance using the Web API :rocket:

[![Build Status](https://travis-ci.org/nidup/akeneo-data-generator.png)](https://travis-ci.org/nidup/akeneo-data-generator)

## Install

Last Akeneo PIM 2.0.x is installed.

### Local Install

- Install Composer & PHP 7.1.
- Clone this repository and `cd` into it.
- Run `composer.phar update --prefer-dist` to install the project's dependencies.

### Docker Install

- Install [Docker Engine](https://docs.docker.com/engine/installation/)
- Install [Docker Compose](https://docs.docker.com/compose/install/)
- Clone this repository and `cd` into it.
- Run `docker-compose pull`.
- Run `docker-compose up -d`.
- Run `bin/docker/composer.sh update --prefer-dist` to install the project's dependencies.

## Configure

Connect to Akeneo PIM UI, then go to System > API Connections.

Create new credentials (client_id & secret).

Copy & paste app/parameters.yml.dist to app/parameters.yml.

Complete app/parameters.yml with the credentials.

## Use

- Run `bin/docker/console.sh` to run the `bin/console` script.

### Generate Category Trees

For instance, to generate 1 category tree with 99 children categories on 3 levels.

- Run `bin/docker/console.sh akeneo:api:generate-category-trees 1 100 3`

### Generate Channels

For instance, to generate 2 channels.

- Run `bin/docker/console.sh akeneo:api:generate-channels 2`

### Generate Attributes

For instance, to generate 100 attributes.

- Run `bin/docker/console.sh akeneo:api:generate-attributes 100 --useable-in-grid`

### Generate Families

For instance, to generate 100 families.

- Run `bin/docker/console.sh akeneo:api:generate-family 100`

### Generate Complete Products

The generation is based on structure of the targeted PIM, it picks a random family and creates a product filling all values.

- Run `bin/docker/console.sh akeneo:api:generate-products 100 --with-images`

Please note that data generation takes time, especially the image generation part that has a large impact on performance.

### Generate Complete Catalog

The generation is based on a configuration file that needs to be placed into `app/catalog/`.

- Run `bin/docker/console.sh akeneo:api:generate-catalog small.yml --check-minimal-install --with-products`

The tool is not optimized, it takes ~5 min to generate & import the small catalog including products.

When generating & injecting large set of products, for instance, 300K products, we advise to launch several product generation commands in parallel.

Troubleshooting: on Enterprise Edition, you need to give permissions on category trees before to launch the product import, this issue is known as PIM-6937 and will be fixed in a 2.0.x patch.

## Credits

Thanks @fzaninotto for Faker!

Thanks @matthiasnoback for the devtools docker images!