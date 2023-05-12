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

Default values : XX=1.12.0 and YY=6.2 and ZZ=8.1

:information_source: To reset (drop database and delete files) test environment:
```bash
$ make reset
```

### PHPUnit

After setup your environment, you can run the PHPUnit tests

```bash
$ make phpunit
```

### Opening Sylius with your plugin

Yon can also see Sylius with this plugin by pointing your browser to _ThisPlugin_/tests/Application/public/index.php


## Other

### Running code analyse

- GrumPHP (see configuration [grumphp.yml](grumphp.yml).)

  GrumPHP is executed by the Git pre-commit hook, but you can launch it manualy with :

  ```bash
  $ make grumphp
  ```

### Run a server

If you don't have a Docker environment or a local server, you can use the Symfony server to run Sylius.

- Using `test` environment:

    ```bash
    $ (cd tests/Application && bin/console server:run -d public -e test)
    ```

- Using `dev` environment:

    ```bash
    $ (cd tests/Application && bin/console server:run -d public -e dev)
    ```
