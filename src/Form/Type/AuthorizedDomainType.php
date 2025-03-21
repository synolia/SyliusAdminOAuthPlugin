<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Synolia\SyliusAdminOauthPlugin\Entity\Domain\AuthorizedDomain;

final class AuthorizedDomainType extends AbstractResourceType
{
    public function __construct(
        #[Autowire(AuthorizedDomain::class)]
        string $dataClass,
        #[Autowire(['sylius', 'default'])]
        array $validationGroups = [],
    ) {
        parent::__construct($dataClass, $validationGroups);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'synolia.sylius_admin_oauth.form.authorized_domains.name',
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'synolia.sylius_admin_oauth.form.authorized_domains.authorize',
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'synolia_admin_oauth_authorized_domain';
    }
}
