<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Security;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Contracts\Translation\TranslatorInterface;
use Synolia\SyliusAdminOauthPlugin\Repository\AuthorizedDomainRepository;
use Synolia\SyliusAdminOauthPlugin\Service\UserCreationService;

final class MicrosoftAuthenticator extends OAuth2Authenticator
{
    public function __construct(
        private ClientRegistry $clientRegistry,
        private TranslatorInterface $translator,
        private RouterInterface $router,
        private UserCreationService $userCreationService,
        private AuthorizedDomainRepository $authorizedDomainRepository
    ) {
    }

    public function supports(Request $request): ?bool
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return 'connect_microsoft_check' === $request->attributes->get('_route');
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
        $client = $this->clientRegistry->getClient('azure_main');
        $accessToken = $this->fetchAccessToken($client);

        $microsoftUser = $client->fetchUserFromToken($accessToken);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use ($microsoftUser) {
                $domains = $this->authorizedDomainRepository->findBy(['isEnabled' => true]);
                // If there's no domains -> first use of the plugin -> connect
                if (0 === \count($domains)) {
                    return $this->userCreationService->createByMicrosoftAccount($microsoftUser);
                }
                // Else connect compared to authorized domains
                foreach ($domains as $domain) {
                    if (
                        str_ends_with((string) $microsoftUser->getEmail(), $domain->getName())
                    ) {
                        return $this->userCreationService->createByMicrosoftAccount($microsoftUser);
                    }
                }
                $translatedMessage = $this->translator->trans('sylius.microsoft_authentication.domain_error');
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
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?RedirectResponse
    {
        $translatedMessage = $this->translator->trans('sylius.microsoft_authentication.authentication_failure');
        /** @var Session $session */
        $session = $request->getSession();
        $session->getFlashBag()->add('danger', $translatedMessage);

        return new RedirectResponse('/admin/login', 302);
    }
}
