<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Security;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Contracts\Translation\TranslatorInterface;
use Synolia\SyliusAdminOauthPlugin\Repository\AuthorizedDomainRepository;
use Synolia\SyliusAdminOauthPlugin\Service\UserCreationService;

final class GoogleAuthenticator extends OAuth2Authenticator
{
    public function __construct(
        private ClientRegistry $clientRegistry,
        private TranslatorInterface $translator,
        private RouterInterface $router,
        private UserCreationService $userCreationService,
        private AuthorizedDomainRepository $authorizedDomainRepository,
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

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
                foreach ($this->authorizedDomainRepository->findBy(['isEnabled' => true]) as $domain) {
                    if (
                        str_ends_with((string) $googleUser->getEmail(), $domain->getName())
                    ) {
                        return $this->userCreationService->createByGoogleAccount($googleUser);
                    }
                }
                $translatedMessage = $this->translator->trans('sylius.google_authentication.domain_error');
                throw new AuthenticationException($translatedMessage);
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
        return new RedirectResponse(
            $this->router->generate('sylius_admin_dashboard')
        );
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $translatedMessage = $this->translator->trans('sylius.google_authentication.authentication_failure');
        /** @var Session $session */
        $session = $request->getSession();
        $session->getFlashBag()->add('danger', $translatedMessage);

        $adminPage = $this->urlGenerator->generate('sylius_admin_login');

        return new RedirectResponse($adminPage, 302);
    }
}
