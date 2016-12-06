<?php
/**
 * Created by PhpStorm.
 * User: robertas
 * Date: 16.11.30
 * Time: 20.39
 */

namespace AppBundle\Form;

use AppBundle\Entity\Result;
use AppBundle\Form\DataTransformer\ActivityToNumberTransformer;
use AppBundle\Form\DataTransformer\StudentInfoToNumberTransformer;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResultType extends AbstractType
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('value', NumberType::class, array('required' => false))
            ->add('activity', HiddenType::class)
            ->add('studentInfo', HiddenType::class);

        $builder->get('activity')
            ->addModelTransformer(new ActivityToNumberTransformer($this->em));
        $builder->get('studentInfo')
            ->addModelTransformer(new StudentInfoToNumberTransformer($this->em));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
            'data_class' => Result::class
            )
        );
    }
}
