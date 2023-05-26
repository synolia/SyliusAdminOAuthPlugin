[![License](https://img.shields.io/packagist/l/synolia/sylius-admin-oauth-plugin.svg)](https://github.com/synolia/SyliusAdminOauthPlugin/blob/master/LICENSE)
![Tests](https://github.com/synolia/SyliusAdminOauthPlugin/workflows/CI/badge.svg?branch=master)
[![Version](https://img.shields.io/packagist/v/synolia/sylius-admin-oauth-plugin.svg)](https://packagist.org/packages/synolia/sylius-admin-oauth-plugin)
[![Total Downloads](https://poser.pugx.org/synolia/sylius-admin-oauth-plugin/downloads)](https://packagist.org/packages/synolia/sylius-admin-oauth-plugin)

<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="docs/sylius_logo.png" />
    </a>
</p>

<h1 align="center">Synolia SyliusAdminOauthPlugin</h1>

<p align="center">oauth for sylius admin</p>

## Features

* TODO

## Requirements

|        | Version  |
|:-------|:---------|
| PHP    | ^8.0     |
| Sylius | ^1.10    |

## Installation

1. Add the bundle and dependencies in your composer.json :
    ```shell script
    $ composer require synolia/sylius-admin-oauth-plugin
    ```
2. Write your google client Id and client secret in you .env file with those keys :
    ```dotenv script
    OAUTH_GOOGLE_CLIENT_ID=
    OAUTH_GOOGLE_CLIENT_SECRET=
    ```
3. In your security.yaml, add a custom authenticator in your admin firewall and put 2 more access_control paths wich must be on top of the others :
    ```yaml script
    security:
      enable_authenticator_manager: true
      firewalls:
        admin:
            custom_authenticators:
                - Synolia\SyliusAdminOauthPlugin\Security\GoogleAuthenticator
    
      access_control:
        - { path: "%sylius.security.admin_regex%/connect/google",       role: PUBLIC_ACCESS }
        - { path: "%sylius.security.admin_regex%/connect/google/check", role: PUBLIC_ACCESS }
    ```
4. Paste it in your config\packages\\_sylius.yaml :
    ```yaml script
       sylius_ui:
           events:
               sylius.admin.login.form.content:
                   blocks:
                      my_block_name: '@SynoliaSyliusAdminOauthPlugin/google_auth_button.html.twig'
    ```
4. Create config/packages/knpu_oauth2_client.yaml :
    ```yaml script
       knpu_oauth2_client:
            clients:
                google_main:
                    type: google
                    client_id: '%env(OAUTH_GOOGLE_CLIENT_ID)%'
                    client_secret: '%env(OAUTH_GOOGLE_CLIENT_SECRET)%'
                    redirect_route: 'connect_google_check'
                    redirect_params: {}

    ```

5. Add this to your App\Entity\AdminUser.php
    ```php script
   use CustomAdminUserTrait;
   ```
6. Add the migration file to your doctrine_migrations.yaml :
    ```yaml script
    migrations_paths:
        'Synolia\SyliusAdminOauthPlugin\Migrations': '%kernel.project_dir%/vendor/synolia/sylius-admin-oauth-plugin/src/Migrations'
    ```
7. Run migration added in your Migrations directory to give google_id and hosted_domain to your admin user entity :
   ```shell script
    php bin/console doctrine:migrations:migrate
   ```

8. You can now connect to your Google account in admin login pannel !

## Development

See [How to contribute](CONTRIBUTING.md).

## License

This library is under the [EUPL-1.2 license](LICENSE).

## Credits

Developed by [Synolia](https://synolia.com/).
