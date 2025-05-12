[![License](https://img.shields.io/packagist/l/synolia/sylius-admin-oauth-plugin.svg)](https://github.com/synolia/SyliusAdminOauthPlugin/blob/main/LICENSE)
[![CI - Analysis](https://github.com/synolia/SyliusAdminOAuthPlugin/actions/workflows/analysis.yaml/badge.svg?branch=main)](https://github.com/synolia/SyliusAdminOAuthPlugin/actions/workflows/analysis.yaml)
[![CI - Sylius](https://github.com/synolia/SyliusAdminOAuthPlugin/actions/workflows/sylius.yaml/badge.svg?branch=main)](https://github.com/synolia/SyliusAdminOAuthPlugin/actions/workflows/sylius.yaml)
[![Version](https://img.shields.io/packagist/v/synolia/sylius-admin-oauth-plugin.svg)](https://packagist.org/packages/synolia/sylius-admin-oauth-plugin)
[![Total Downloads](https://poser.pugx.org/synolia/sylius-admin-oauth-plugin/downloads)](https://packagist.org/packages/synolia/sylius-admin-oauth-plugin)

<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="docs/sylius_logo.png" />
    </a>
</p>

<h1 align="center">Synolia SyliusAdminOauthPlugin</h1>

<p align="center">Add OAuth authentication for Sylius admin panel</p>

## Features

* Allow your admin users to subscribe and sign in with OAuth providers :
    * Google
    * Microsoft
* Allow domain connexion management.

## Requirements

|        | Version       |
|:-------|:--------------|
| PHP    | ^8.2          |
| Sylius | ^1.12 or ^2.0 |

## Installation

1. Add the bundle and dependencies in your composer.json :
    ```shell
    $ composer require synolia/sylius-admin-oauth-plugin
    ```
2. Write your Google and/or Microsoft client ID and client secret in you .env file with those keys :
    ```dotenv
    SYNOLIA_ADMIN_OAUTH_GOOGLE_CLIENT_ID=
    SYNOLIA_ADMIN_OAUTH_GOOGLE_CLIENT_SECRET=

    SYNOLIA_ADMIN_OAUTH_MICROSOFT_CLIENT_ID=
    SYNOLIA_ADMIN_OAUTH_MICROSOFT_CLIENT_SECRET=
    ```
3. In your security.yaml, add the OAuth authenticator in your admin firewall and put access_control paths you need depending on wich provider you use. **They must be on top of the others** :
    ```yaml
    security:
      firewalls:
        admin:
            custom_authenticators:
                - Synolia\SyliusAdminOauthPlugin\Security\Authenticator\OauthAuthenticator
    
      access_control:
        - { path: "%sylius.security.admin_regex%/connect/google", role: PUBLIC_ACCESS, requires_channel: https }
        - { path: "%sylius.security.admin_regex%/connect/google/check", role: PUBLIC_ACCESS, requires_channel: https }
   
        - { path: "%sylius.security.admin_regex%/connect/microsoft", role: PUBLIC_ACCESS, requires_channel: https }
        - { path: "%sylius.security.admin_regex%/connect/microsoft/check", role: PUBLIC_ACCESS, requires_channel: https }
    ```

4. Create a config/routes/synolia_oauth.yaml to configure plugin's routes and to prefix them with 'admin':
   ```yaml
    synolia_oauth:
        resource: '@SynoliaSyliusAdminOauthPlugin/config/routes[-legacy].yaml'
        prefix: '/%sylius_admin.path_name%'
   ```
5. Create a config/packages/synolia_oauth_config.yaml to import all required configs 
   depending on which sylius version you use:
    ```yaml
    imports:
      - { resource: "@SynoliaSyliusAdminOauthPlugin/config/app[-legacy].yaml" }
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
7. Apply migrations to your database:
   ```shell
    php bin/console doctrine:migrations:migrate
   ```

8. After the first installation, no domain is configured so you have to add one to be able to connect with OAuth.
   If you already have admin users, add one through the administration panel and authorize it. You can access it through OAuth domain administration section in the menu.



Don't forget to add your Allowed redirect URIs in Google cloud console or Azure Active Directory !
Full documentation here : 
* Google: https://cloud.google.com/looker/docs/admin-panel-authentication-google 
* Microsoft: https://learn.microsoft.com/en-en/azure/active-directory/architecture/auth-oauth2


You can now connect to your accounts with OAuth in the admin login panel !

## Troubleshooting

- Error 'TOO_MANY_REDIRECT' : add these two lines
  - services.yaml:
    ```yaml
    parameters:
      router.request_context.scheme: 'https'
    ```
   - framework.yaml:
    ```yaml
    framework:
      trusted_proxies: '127.0.0.1,REMOTE_ADDR'
    ```
- If you don't see your oauth connexion button, verify your .env variables where your client_id and client_secret are specified
- If you have "Impossible to connect, try again" message, **don't forget to configure your authorized domains in back-office.**

## Development

See [How to contribute](CONTRIBUTING.md).

## License

This library is under the [EUPL-1.2 license](LICENSE).

## Credits

Developed by [Synolia](https://synolia.com/).
