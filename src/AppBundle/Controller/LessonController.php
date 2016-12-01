<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Lesson;
use AppBundle\Entity\Result;
use AppBundle\Exception\LessonException;
use AppBundle\Form\ResultSet;
use AppBundle\Form\ResultSetType;
use Doctrine\ORM\NoResultException;
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
        }
        catch (LessonException $e) {
            $this->addFlash('notice', $e->getMessage());
            return $this->render('@App/Lesson/errors.html.twig');
        }
    }

    /**
     * @Route("/lesson/{id}", name="lesson")
     */
    public function lessonAction($id, Request $request)
    {
        try {
            $lesson = $this->get('app.lesson_service')->getLesson($id);
        } catch (NoResultException $e) {
            $this->addFlash('notice', "Pamoka nerasta");
            return $this->render('@App/Lesson/errors.html.twig');
        }

        return $this->display($lesson, 'Pamoka', $request);
    }

    /**
     * @param  Lesson $lesson
     * @param  string $title
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function display($lesson, $title, $request)
    {
        $id         = $lesson->getId();
        $classInfo  = $lesson->getClassInfo();

        $students   = $this->get('app.student_info')->getStudentListByClass($classInfo);
        $activities = $this->get('app.activity')->getActivityList();
        $results    = $this->get('app.result')->getLastResultsByClass($classInfo);

        try {
            $nextLesson = $this->get('app.lesson_service')->getNext($id);
        } catch (NoResultException $e) {
            $nextLesson = false;
        }

        try {
            $prevLesson = $this->get('app.lesson_service')->getPrev($id);
        } catch (NoResultException $e) {
            $prevLesson = false;
        }

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
            'title'      => $title,
            'lesson'     => $lesson,
            'nextLesson' => $nextLesson,
            'prevLesson' => $prevLesson,
            'students'   => $students,
            'activities' => $activities,
            'results'    => $results,
            'form'       => $form->createView()
        ]);
    }

}
