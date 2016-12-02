<?php

namespace AppBundle\Form;

use AppBundle\Entity\Activity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
            ->add(
                'bestResultDetermination',
                ChoiceType::class,
                array(
                'choices'  => array(
                    'Didžiausias rezultatas' => Activity::BEST_RESULT_DETERMINATION_MAX,
                    'Mažiausias rezultatas' => Activity::BEST_RESULT_DETERMINATION_MIN
                ))
            )
            ->add('units');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
            'data_class' => Activity::class
            )
        );
    }
}
