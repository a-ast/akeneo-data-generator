#!/usr/bin/env bash

docker-compose run --rm devtools /bin/bash -c "php app/console $1 $2 $3 $4 $5"
