## php-grade

With php-grade you can report your own project QA statistics. It makes use of some of [The PHP Quality Assurance Toolchain](http://phpqatools.org/) like phpmd

## Installation


## Run

Run this from the git cloned directory and open the given url.

```bash
bin/php-grade run -vvv src --serve
```

### Options

Checkout the available options.

```bash
bin/php-grade help run
```

### Example

```bash
bin/php-grade run \
  --verbose \
  --tests=phpcs \
  --tests=phpdcd \
  --output-dir=../gh-pages/php-grade/stats/ \
  src
```

## Visit stats

http://build2be.github.io/php-grade/stats/
