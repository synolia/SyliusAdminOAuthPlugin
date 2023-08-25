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

* Allow your admin users to subscribe / connect with Oauth providers.
* Allow domain connexion management.

## Requirements

|        | Version  |
|:-------|:---------|
| PHP    | ^8.0     |
| Sylius | ^1.10    |

## Installation

1. Add the bundle and dependencies in your composer.json :
    ```shell
    $ composer require synolia/sylius-admin-oauth-plugin
    ```
2. Write your Google and/or Microsoft client Id and client secret in you .env file with those keys :
    ```dotenv
    OAUTH_GOOGLE_CLIENT_ID=
    OAUTH_GOOGLE_CLIENT_SECRET=

    OAUTH_MICROSOFT_CLIENT_ID=
    OAUTH_MICROSOFT_CLIENT_SECRET=
    ```
3. In your security.yaml, add the Oauth authenticator in your admin firewall and put access_control paths you need depending on wich provider you use. **They must be on top of the others** :
    ```yaml
    security:
      enable_authenticator_manager: true
      firewalls:
        admin:
            custom_authenticators:
                - Synolia\SyliusAdminOauthPlugin\Security\Authenticator\OauthAuthenticator
    
      access_control:
        - { path: "%sylius.security.admin_regex%/connect/google",       role: PUBLIC_ACCESS, requires_channel: https }
        - { path: "%sylius.security.admin_regex%/connect/google/check", role: PUBLIC_ACCESS, requires_channel: https }
   
        - { path: "%sylius.security.admin_regex%/connect/microsoft",       role: PUBLIC_ACCESS, requires_channel: https }
        - { path: "%sylius.security.admin_regex%/connect/microsoft/check", role: PUBLIC_ACCESS, requires_channel: https }
    ```

4. Create a config/routes/synolia_oauth.yaml to configure plugin's routes and to prefix them with 'admin':
   ```yaml
    synolia_oauth:
        resource: '@SynoliaSyliusAdminOauthPlugin/config/routes.yaml'
        prefix: '/%sylius_admin.path_name%'
   ```
5. Create a config/packages/synolia_oauth_config.yaml to import all required configs :
    ```yaml
    imports:
      - { resource: "@SynoliaSyliusAdminOauthPlugin/config/app.yaml" }
    ```

6. Add this trait to your App\Entity\User\AdminUser.php
    ```php
    use Doctrine\ORM\Mapping as ORM;
    use Sylius\Component\Core\Model\AdminUser as BaseAdminUser;
    use Synolia\SyliusAdminOauthPlugin\Entity\User\CustomAdminUserTrait;
    
    class AdminUser extends BaseAdminUser
    {
         use CustomAdminUserTrait;
    }
    ```
7. Run migration to give google_id and hosted_domain to your admin user entity and create Authorized domain table:
   ```shell
    php bin/console doctrine:migrations:migrate
   ```
   
Don't forget to add your Allowed redirect URIs in google cloud console ! 
Full documentation here : https://cloud.google.com/looker/docs/admin-panel-authentication-google

You can now connect to your Google account in admin login pannel !

## Development

See [How to contribute](CONTRIBUTING.md).

## License

This library is under the [EUPL-1.2 license](LICENSE).

## Credits

Developed by [Synolia](https://synolia.com/).
