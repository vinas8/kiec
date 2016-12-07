<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, array('label' => 'form.name', 'translation_domain' => 'FOSUserBundle'));
        $builder->add(
            'profile_picture',
            FileType::class,
            array('label' => 'form.profile_picture', 'translation_domain' => 'FOSUserBundle', 'required' => false)
        );
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
