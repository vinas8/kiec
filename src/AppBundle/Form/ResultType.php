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
use AppBundle\Entity\Result;
use AppBundle\Entity\StudentInfo;
use AppBundle\Entity\User;
use AppBundle\Service\ActivityService;
use AppBundle\Service\CurrentUserDataService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResultType extends AbstractType
{
    private $em;

    /**
     * @return User
     */
    private $currentUser;
    private $activityList;

    public function __construct(EntityManager $em, CurrentUserDataService $currentUserDataService, ActivityService $activityService)
    {
        $this->em = $em;
        $this->currentUser = $currentUserDataService->getUser();
        $this->activityList = $activityService->getActivityList();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $user = $this->currentUser->getMainStudentInfo()->getClassInfo()->getUser();
//        dump($user);
        $users = new ArrayCollection();
        $users->add($this->currentUser);
        foreach ($this->currentUser->getStudents() as $student) {
            foreach ($student->getClassInfo()->getUser() as $teacher) {
                $users->add($teacher);
            }
        }



        $builder->add('value', NumberType::class)
            ->add('activity', EntityType::class, array(
                'class' => Activity::class,
                'choices' => $this->activityList,
                'choice_label' => 'name',
            ));
        if (in_array("ROLE_TEACHER", $this->currentUser->getRoles())) {
            $builder->add('studentInfo', EntityType::class, array(
                'class' => StudentInfo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->innerJoin('s.classInfo', 'c', 'WITH', ':user MEMBER OF c.user')
                        ->setParameter('user', $this->currentUser)
                        ->orderBy('s.name');
                },
                'choice_label' => 'name',
            ));
        }
        $builder->add('timestamp', DateTimeType::class, array(
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'view_timezone' => 'Europe/Vilnius'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Result::class,
            )
        );
    }
}
