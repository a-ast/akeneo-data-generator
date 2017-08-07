# Akeneo PHP API Client sandbox

Playing with the Akeneo PHP API client :rocket:

## Requirements

- [Docker Engine](https://docs.docker.com/engine/installation/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Getting started

Have a Akeneo PIM 1.8 installed.

## Generate a web api token from your PIM (here the dev version installed through official docker images)

Run `docker-compose exec fpm bin/console pim:oauth-server:create-client`

The command will return the following credentials:

```
A new client has been added.
client_id: myClientId
secret: mySecret
```

## Install this repository

- Clone this repository and `cd` into it.
- Run `docker-compose pull`.
- Run `bin/composer.sh install --prefer-dist` to install the project's dependencies.

## Use the web api sandbox

- Run `bin/console.sh` to run the `app/console` script.

## Run the test command

- Run `bin/console.sh nidup:sandbox:test myIp myPort myClientId mySecret` to run the sandbox command.

## Thanks

@matthiasnoback for the nice devtools docker images ;)
