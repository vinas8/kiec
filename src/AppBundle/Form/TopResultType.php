<?php
/**
 * Created by PhpStorm.
 * User: robertas
 * Date: 16.11.30
 * Time: 20.39
 */

namespace AppBundle\Form;

use AppBundle\DBAL\Types\OriginType;
use AppBundle\Entity\Activity;
use AppBundle\Entity\ClassInfo;
use AppBundle\Entity\Result;
use AppBundle\Entity\StudentInfo;
use AppBundle\Service\CurrentUserDataService;
use AppBundle\Utils\TopResultSet;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TopResultType extends AbstractType
{
    private $em;

    private $currentUser;

    public function __construct(EntityManager $em, CurrentUserDataService $currentUserDataService) {
        $this->em = $em;
        $this->currentUser = $currentUserDataService->getUser();
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('activity', EntityType::class, array(
            'class' => Activity::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('a')
                    ->where('a.user = :user')
                    ->orWhere('a.origin = :origin')
                    ->setParameter('user', $this->currentUser)
                    ->setParameter('origin', OriginType::NATIVE)
                    ->orderBy('a.name');
            },
            'choice_label' => 'name',
        ))
            ->add('classInfo', EntityType::class, array(
                'class' => ClassInfo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->where(':user MEMBER OF s.user')
                        ->setParameter('user', $this->currentUser)
                        ->orderBy('s.name');
                },
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
            ))
            ->add('maxResults', NumberType::class, array(
                'data' => 10
            ));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(
            array(
                'data_class' => TopResultSet::class,
            )
        );
    }
}
