<?php
/**
 * Created by PhpStorm.
 * User: robertas
 * Date: 16.11.21
 * Time: 21.42
 */

namespace AppBundle\Form;


use AppBundle\Service\StudentInfoService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResultType extends AbstractType
{

    /**
     * @var StudentInfoService
     */
    private $studentInfoService;

    public function __construct(StudentInfoService $studentInfoService)
    {
        $this->studentInfoService = $studentInfoService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("activity", HiddenType::class);
        $students = $this->studentInfoService->getStudentListByClass($options["classInfo"]);
        foreach ($students as $student) {
            $builder->add("student_".$student->getId(), NumberType::class, array("required" => false));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'classInfo' => null,
        ));
    }
}