<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Synolia\SyliusAdminOauthPlugin\Security\GoogleAuthenticator;

class GoogleController extends AbstractController
{
    public function __construct(
        private readonly ClientRegistry      $clientRegistry,
        private readonly GoogleAuthenticator $authenticator,
    )
    {
    }

//    #[Route('/connect/google', name: 'connect_google')]
    public function connectAction(): RedirectResponse
    {
        return $this->clientRegistry->getClient('google_main')->redirect([], []);
    }

//    #[Route('/connect/google/check', name: 'connect_google_check')]
    public function connectCheckAction(Request $request)
    {
        try {
            $passport = $this->authenticator->authenticate($request);
            $user = ($passport->getUser());
        } catch (\Exception $e) {
            $this->addFlash("danger", $e->getMessage());
        }

        return $this->redirectToRoute('sylius_admin_dashboard');
    }
}
