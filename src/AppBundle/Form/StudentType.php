<?php

namespace AppBundle\Form;

use AppBundle\DBAL\Types\BestResultDeterminationType;
use AppBundle\Entity\Activity;
use AppBundle\Entity\ClassInfo;
use AppBundle\Entity\StudentInfo;
use AppBundle\Service\CurrentUserDataService;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentType extends AbstractType
{
    private $currentUser;

    public function __construct(CurrentUserDataService $currentUserDataService) {
        $this->currentUser = $currentUserDataService->getUser();
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name')
            ->add('birthDate', DateType::class, array('widget' => 'single_text'))
            ->add('classInfo', EntityType::class, array(
                'class' => ClassInfo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->where(':user MEMBER OF s.user')
                        ->setParameter('user', $this->currentUser)
                        ->orderBy('s.name');
                },
                'choice_label' => 'name'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(
            array(
                'data_class' => StudentInfo::class
            )
        );
    }
}
