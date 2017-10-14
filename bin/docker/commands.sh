#!/usr/bin/env bash

docker-compose run --rm devtools /bin/bash -c "php bin/console akeneo:sandbox:get-first-product"
docker-compose run --rm devtools /bin/bash -c "php bin/console akeneo:sandbox:generate-attributes 10"
docker-compose run --rm devtools /bin/bash -c "php bin/console akeneo:sandbox:generate-families 10"
docker-compose run --rm devtools /bin/bash -c "php bin/console akeneo:sandbox:generate-products 10 --with-images"
