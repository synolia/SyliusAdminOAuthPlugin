<?php

declare(strict_types=1);

namespace Tests\Synolia\SyliusAdminOauthPlugin\PHPUnit;

use App\Entity\User\AdminUser;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @internal
 */
final class AccessToAdminAreaTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->client->followRedirects();
    }

    public function testAccessToAdminArea(): void
    {
        $this->client->request('GET', '/admin/login');

        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Sylius | Administration panel login');

        $this->adminLogin();
        $this->client->request('GET', '/admin/');

        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Dashboard Sylius');
    }

    public function testAccessOAuthConfigPageInAdmin(): void
    {
        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
        $url = $urlGenerator->generate('synolia_admin_oauth_admin_authorized_domain_index');

        $this->adminLogin();
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();

        self::assertSame(200, $response->getStatusCode(), 'Unable to get successful to config page in Admin Area.');
    }

    private function adminLogin(): void
    {
        /** @var EntityManager $manager */
        $manager = self::$kernel->getContainer()->get('doctrine')->getManager();

        $user = $manager->getRepository(AdminUser::class)->findOneBy(['username' => 'sylius']);
        $this->client->loginUser($user, 'admin');
    }
}
