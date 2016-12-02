<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Lesson;
use AppBundle\Entity\Result;
use AppBundle\Exception\LessonException;
use AppBundle\Form\ResultSet;
use AppBundle\Form\ResultSetType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class LessonController extends Controller
{
    /**
     * @Route("/current", name="current")
     */
    public function currentAction(Request $request)
    {
        try {
            $currentLesson = $this->get('app.lesson_service')->getCurrentLesson();
            return $this->display($currentLesson, 'Dabartinė pamoka', $request);
        } catch (LessonException $e) {
            $this->addFlash('notice', $e->getMessage());
            return $this->render('@App/Lesson/errors.html.twig');
        }
    }

    /**
     * @Route("/lesson/{Lesson}", name="lesson")
     */
    public function lessonAction(Lesson $Lesson = null, Request $request)
    {
        if (!$Lesson) {
            $this->addFlash('danger', 'Tokia pamoka neegzistuoja!');
            return $this->redirectToRoute('homepage');
        }

        return $this->display($Lesson, 'Pamoka', $request);
    }

    /**
     * @param  Lesson $lesson
     * @param  string $title
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function display(Lesson $lesson, $title, $request)
    {
        $classInfo = $lesson->getClassInfo();

        $students = $this->get('app.student_info')->getStudentListByClass($classInfo);
        $activities = $this->get('app.activity')->getActivityList();
        $results = $this->get('app.result')->getLastResultsByClass($classInfo);
        $nextLesson = $this->get('app.lesson_service')->getNext($lesson);
        $prevLesson = $this->get('app.lesson_service')->getPrev($lesson);

        $resultSet = new ResultSet();
        foreach ($students as $student) {
            $result = new Result();
            $resultSet->getResults()->add($result);
        }
        $form = $this->createForm(ResultSetType::class, $resultSet);

        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $newResults = $form->getData();
            foreach($newResults->getResults() as $newResult) {
                if ($newResult->getValue() !== null) {
                    $newResult->setTimestamp(new \DateTime());
                    $em->persist($newResult);
                }
            }
            $em->flush();
        }

        return $this->render('AppBundle:Lesson:lesson.html.twig', [
            'title' => $title,
            'lesson' => $lesson,
            'nextLesson' => $nextLesson,
            'prevLesson' => $prevLesson,
            'students' => $students,
            'activities' => $activities,
            'results' => $results,
            'form' => $form->createView()
        ]);
    }
}
