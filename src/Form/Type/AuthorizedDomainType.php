<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class AuthorizedDomainType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'app.form.authorized_domains.name',
            ])
            ->add('isEnabled', CheckboxType::class, [
                'label' => 'app.form.authorized_domains.authorize',
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'authorized_domain';
    }
}
