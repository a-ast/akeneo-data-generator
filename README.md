# Akeneo PHP Client sandbox

Play with the Akeneo php client

## Requirements

- [Docker Engine](https://docs.docker.com/engine/installation/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Getting started

- Clone this repository and `cd` into it.
- Run `docker-compose pull`.
- Run `bin/composer.sh install --prefer-dist` to install the project's dependencies.

## Generate a web api token for your PIM

Run `docker-compose exec akeneo app/console pim:oauth-server:create-client`

The command will return the following credentials:

```
client_id: myClientId
secret: mySecret
```

## Running the web api sandbox

- Run `bin/console.sh` to run the `app/console` script.
- Run `bin/console.sh nidup:sandbox 192.168.7.175 8080 myClientId mySecret`

## Thanks

@matthiasnoback for the nice devtools docker images ;)
