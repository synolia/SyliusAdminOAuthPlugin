<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Security;

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

final class GoogleAuthenticator extends OAuth2Authenticator
{
    /**
     * @param ClientRegistry $clientRegistry
     * @param RouterInterface $router
     * @param UserCreationService $userCreationService
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(
        readonly ClientRegistry $clientRegistry,
        readonly RouterInterface $router,
        readonly UserCreationService $userCreationService,
    ) {
    }

    /**
     * @param Request $request
     *
     * @return bool|null
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function supports(Request $request): ?bool
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return 'connect_google_check' === $request->attributes->get('_route');
    }

    /**
     * @param Request $request
     *
     * @return Passport
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('google_main');
        $accessToken = $this->fetchAccessToken($client);
        /** @var GoogleUser $googleUser */
        $googleUser = $client->fetchUserFromToken($accessToken);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use ($googleUser) {
                if (str_ends_with((string) $googleUser->getEmail(), '@synolia.com')) {
                    return $this->userCreationService->createByGoogleAccount($googleUser);
                }
                throw new AuthenticationException('Vous ne pouvez créer de compte administrateur.');
            })
        );
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $firewallName
     *
     * @return Response|null
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // change "app_dashboard" to some route in your app
        return new RedirectResponse(
            $this->router->generate('sylius_admin_dashboard')
        );
        // or, on success, let the request continue to be handled by the controller
        // return null;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return Response|null
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }
}
