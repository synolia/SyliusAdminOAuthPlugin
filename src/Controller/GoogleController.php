<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class GoogleController extends AbstractController
{
    #[Route('/connect/google', name: 'connect_google')]
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
    #[Route('/connect/google/check', name: 'connect_google_check')]
    public function connectCheckAction(Request $request): void
    {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a Guard authenticator
    }
}
