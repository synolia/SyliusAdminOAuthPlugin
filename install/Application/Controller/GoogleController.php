<?php

//namespace Synolia\SyliusAdminOauthPlugin\Controller;
namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Security\GoogleAuthenticator;

#[Route('connect', name: 'connect')]
class GoogleController extends AbstractController
{
    public function __construct(
        private readonly ClientRegistry $clientRegistry,
    )
    {
    }

    #[Route('/google', name: 'connect_google')]
    public function connectAction(): RedirectResponse
    {
        //Redirect to google
        return $this->clientRegistry->getClient('google')->redirect([], []);
    }

    /**
     * After going to Google, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     */
    #[Route('/google/check', name: 'connect_google_check')]
    public function connectCheckAction(Request $request, GoogleAuthenticator $authenticator): RedirectResponse
    {
        try {
            $authenticator->authenticate($request);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return $this->redirectToRoute('sylius_admin_dashboard');
    }
}
