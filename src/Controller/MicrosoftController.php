<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final class MicrosoftController extends AbstractController
{
    #[Route('/connect/microsoft', name: 'connect_admin_azure')]
    public function connectAction(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry->getClient('azure_admin')->redirect([], []);
    }

    #[Route('/connect/microsoft/check', name: 'connect_admin_microsoft_check')]
    public function connectCheckAction(): void
    {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a Guard authenticator
    }
}
