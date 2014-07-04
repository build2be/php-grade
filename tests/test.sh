#!/bin/bash
rm -rf /tmp/atest
mkdir /tmp/atest
php bin/php-grade run -f angular -t phpcs -t phpmd -t phpcpd -s .
