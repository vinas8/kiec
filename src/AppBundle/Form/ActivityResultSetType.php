<?php
/**
 * Created by PhpStorm.
 * User: robertas
 * Date: 16.11.30
 * Time: 20.28
 */

namespace AppBundle\Form;

use AppBundle\Utils\ActivityResultSet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityResultSetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'activities',
            CollectionType::class,
            array(
                'entry_type' => ResultSetType::class
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => ActivityResultSet::class,
            )
        );
    }
}
