<?php

namespace Synolia\SyliusAdminOauthPlugin\Security;

use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Synolia\SyliusAdminOauthPlugin\Service\UserCreationService;

class GoogleAuthenticator extends OAuth2Authenticator
{
    public function __construct(
        readonly ClientRegistry $clientRegistry,
        readonly EntityManagerInterface $entityManager,
        readonly RouterInterface $router,
        readonly  UserCreationService $userCreationService,
    )
    {
    }

    public function supports(Request $request): ?bool
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'connect_google_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('google_main');
        $accessToken = $this->fetchAccessToken($client);
        /** @var GoogleUser $googleUser */
        $googleUser = $client->fetchUserFromToken($accessToken);
        $email = $googleUser->getEmail();

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use ($googleUser, $email, $accessToken, $client) {
                if (str_ends_with($email, "@synolia.com")) {
                   return $this->userCreationService->createByGoogleAccount($googleUser);
                }
                else{
                    throw new AuthenticationException("Vous ne pouvez créer de compte administrateur.");
                }
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // change "app_dashboard" to some route in your app
        return new RedirectResponse(
            $this->router->generate('sylius_admin_dashboard')
        );
        // or, on success, let the request continue to be handled by the controller
        //return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }
}
