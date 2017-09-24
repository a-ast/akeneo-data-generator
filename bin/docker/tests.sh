#!/usr/bin/env bash

docker-compose run --rm devtools bin/php-cs-fixer fix src --dry-run -v --diff --rules=@PSR2
docker-compose run --rm devtools bin/php-coupling-detector detect --config-file=.php_cd.php
docker-compose run --rm devtools bin/phpspec run
