<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

final class GoogleController extends AbstractController
{
    public function connectAction(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry->getClient('google_main')->redirect([], []);
    }

    /**
     * After going to google, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function connectCheckAction(Request $request): void
    {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a Guard authenticator
    }
}
