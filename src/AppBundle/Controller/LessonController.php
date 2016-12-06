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
use Symfony\Component\HttpFoundation\Response;

class LessonController extends Controller
{
    /**
     * @Route("/current", name="current")
     *
     * @param  Request $request
     * @return Response
     */
    public function currentAction(Request $request)
    {
        try {
            $currentLesson = $this->get('app.lesson_service')->getCurrentLesson();
            return $this->display($currentLesson, 'Dabartinė pamoka', $request);
        } catch (LessonException $e) {
            $this->addFlash('info', $e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/lesson/{lesson}", name="lesson")
     *
     * @param  Request $request
     * @param  Lesson $lesson
     * @return Response
     */
    public function lessonAction(Request $request, Lesson $lesson = null)
    {
        if (!$lesson) {
            $this->addFlash('danger', 'Tokia pamoka neegzistuoja!');
            return $this->redirectToRoute('homepage');
        }

        return $this->display($lesson, 'Pamoka', $request);
    }

    /**
     * @param  Lesson  $lesson
     * @param  string  $title
     * @param  Request $request
     * @return Response
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
            foreach ($newResults->getResults() as $newResult) {
                if ($newResult->getValue() !== null) {
                    $newResult->setTimestamp(new \DateTime());
                    $em->persist($newResult);
                }
            }
            $em->flush();
            $this->addFlash('success', "Įrašyti nauji rezultatai.");
            return $this->redirect($request->headers->get('referer'));
        }


        return $this->render(
            'AppBundle:Lesson:lesson.html.twig',
            [
            'title' => $title,
            'lesson' => $lesson,
            'nextLesson' => $nextLesson,
            'prevLesson' => $prevLesson,
            'students' => $students,
            'activities' => $activities,
            'results' => $results,
            'form' => $form->createView()
            ]
        );
    }
}
