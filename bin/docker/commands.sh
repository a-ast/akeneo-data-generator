#!/usr/bin/env bash

docker-compose run --rm devtools /bin/bash -c "php bin/console akeneo:api:get-first-product"
docker-compose run --rm devtools /bin/bash -c "php bin/console akeneo:api:generate-attributes 10"
docker-compose run --rm devtools /bin/bash -c "php bin/console akeneo:api:generate-families 10 20"
docker-compose run --rm devtools /bin/bash -c "php bin/console akeneo:api:generate-products 10 --with-images"
docker-compose run --rm devtools /bin/bash -c "php bin/console akeneo:api:generate-catalog test.yml --with-products"
