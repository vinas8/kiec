<?php

namespace AppBundle\Form;

use AppBundle\DBAL\Types\RoleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('username');
        $builder->add('name', null, array('label' => 'form.name', 'translation_domain' => 'FOSUserBundle'));
        $builder->add('role', ChoiceType::class, ['choices' => RoleType::getChoices(), 'mapped' => false]);
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }


    public function getName()
    {
        return 'app_bundle_registration_type';
    }

}
