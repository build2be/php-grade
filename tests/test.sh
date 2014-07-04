#!/bin/bash
mkdir /tmp/atest
php bin/php-grade run -f angular -t phpcs -t phpmd -t phpcpd -o /tmp/atest -s .
