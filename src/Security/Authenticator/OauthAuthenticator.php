<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Security\Authenticator;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Provider\GoogleUser;
use Sylius\Component\Core\Model\AdminUser;
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
use Synolia\SyliusAdminOauthPlugin\Entity\Domain\AuthorizedDomain;
use Synolia\SyliusAdminOauthPlugin\Factory\OauthClientFactory;
use Synolia\SyliusAdminOauthPlugin\Model\OauthClient;
use Synolia\SyliusAdminOauthPlugin\Repository\AuthorizedDomainRepository;
use Synolia\SyliusAdminOauthPlugin\Security\Providers;
use Synolia\SyliusAdminOauthPlugin\Service\UserCreationService;
use TheNetworg\OAuth2\Client\Provider\AzureResourceOwner;
use Webmozart\Assert\Assert;

final class OauthAuthenticator extends OAuth2Authenticator
{
    private ?OauthClient $oauthClient = null;
    private ?string $provider = null;

    public function __construct(
        private ClientRegistry $clientRegistry,
        private TranslatorInterface $translator,
        private RouterInterface $router,
        private UserCreationService $userCreationService,
        private AuthorizedDomainRepository $authorizedDomainRepository,
        private Providers $providers,
        private ?string $googleClientId,
        private ?string $microsoftClientId
    ) {}

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request): ?bool
    {
        $this->getOauthClient($request);

        if (null === $this->oauthClient) {
            return false;
        }

        return $this->oauthClient->getCheckPathName() === $request->attributes->get('_route');
    }

    /**
     * {@inheritDoc}
     */
    public function authenticate(Request $request): Passport
    {
        Assert::isInstanceOf($this->oauthClient, OauthClient::class);
        $client = $this->clientRegistry->getClient($this->oauthClient->getName());
        $accessToken = $this->fetchAccessToken($client);

        /** @var AzureResourceOwner|GoogleUser $user */
        $user = $client->fetchUserFromToken($accessToken);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use ($user) {
                Assert::isInstanceOf($this->oauthClient, OauthClient::class);
                $domains = $this->authorizedDomainRepository->findBy(['isEnabled' => true]);
                // If there's no domains -> first use of the plugin -> connect
                if (0 === \count($domains)) {
                    $translatedMessage = $this->translator->trans('sylius.oauth_authentication.no_configured_domain');
                    throw new AuthenticationException($translatedMessage);
                }
                // Else connect compared to authorized domains
                foreach ($domains as $domain) {
                    if (\array_key_exists($this->oauthClient->getProviderName(), $this->providers->availableProvidersAndControllers)) {
                        return $this->createOauthUserIfDomainCorrespond($user, $domain);
                    }
                }
                $translatedMessage = $this->translator->trans('sylius.oauth_authentication.domain_error');
                throw new AuthenticationException($translatedMessage);
            })
        );
    }

    /**
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
        /** @var Session $session */
        $session = $request->getSession();
        /** @var OauthClient $oauthClient */
        $oauthClient = $this->oauthClient;
        $session->getFlashBag()->add('danger', $this->translator->trans($oauthClient->getAuthenticationFailureMessage()));

        /** Status 401 not authorized because this is a redirection -> code 3** */
        return new RedirectResponse($this->router->generate('sylius_admin_login'), Response::HTTP_PERMANENTLY_REDIRECT);
    }

    private function getOauthClient(Request $request): void
    {
        // If multiple providers are given, get current provider's name if there's one
        if (null !== $this->googleClientId && null !== $this->microsoftClientId) {
            $this->defineProvider($request);
        }

        // If only one provider is given, create corresponding client
        if (null === $this->provider) {
            if (null !== $this->googleClientId) {
                $this->oauthClient = OauthClientFactory::createGoogleOauthClient($this->googleClientId);
            }

            if (null !== $this->microsoftClientId) {
                $this->oauthClient = OauthClientFactory::createMicrosoftOauthClient($this->microsoftClientId);
            }
        }

        // If multiple providers are given, create corresponding client
        match ($this->provider) {
            'google' => $this->oauthClient = OauthClientFactory::createGoogleOauthClient((string) $this->googleClientId),
            'microsoft' => $this->oauthClient = OauthClientFactory::createMicrosoftOauthClient((string) $this->microsoftClientId),
            default => ''
        };
    }

    private function createOauthUserIfDomainCorrespond(AzureResourceOwner|GoogleUser $user, AuthorizedDomain $domain): ?AdminUser
    {
        if (
            $user instanceof GoogleUser &&
            null !== $user->getEmail() &&
            str_ends_with($user->getEmail(), $domain->getName())
        ) {
            return $this->userCreationService->create($user);
        }

        if (
            $user instanceof AzureResourceOwner &&
            null !== $user->getUpn() &&
            str_ends_with($user->getUpn(), $domain->getName())
        ) {
            return $this->userCreationService->create($user);
        }

        return null;
    }

    /**
     * Get providers from ../Providers.php and define it's name if Oauth controller route matches with it
     */
    private function defineProvider(Request $request): void
    {
        /** @var string $controllerPath */
        $controllerPath = $request->attributes->get('_controller', '');

        foreach ($this->providers->availableProvidersAndControllers as $provider => $availableController) {
            if (str_contains($controllerPath, $availableController)) {
                $this->provider = $provider;
            }
        }
    }
}
