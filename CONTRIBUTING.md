## Installation

From the plugin root directory, run the following commands:

```bash
$ composer install
```

Now you can code and commit.
A hook on pre-commit will launch GrumPHP to validate your code.

## Test

### Setup

:information_source: To be able to setup the plugin database, remember to configure you database credentials
in `install/Application/.env.local` and/or `install/Application/.env.test.local`.

```bash
$ make install -e SYLIUS_VERSION=XX SYMFONY_VERSION=YY PHP_VERSION=ZZ
```

Default values : XX=2.0.0 and YY=7.2 and ZZ=8.2

:information_source: To reset (drop database and delete files) test environment:
```bash
$ make reset
```

## Usage

### Running code analyse and tests

- GrumPHP (see configuration [grumphp.yml](grumphp.yml).)

  GrumPHP is executed by the Git pre-commit hook, but you can launch it manualy with :

  ```bash
  $ make grumphp
  ```

- PHPUnit

  ```bash
  $ make phpunit
  ```

### Opening Sylius with your plugin



```bash
$ (cd tests/Application && symfony server:start --dir=public)
```
