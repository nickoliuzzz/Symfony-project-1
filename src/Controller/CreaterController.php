<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Form\AnswerType;
use App\Form\QuestionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreaterController extends Controller
{
    /**
     * @Route("/createquestion/{parametres}",
     *   defaults={"parametres": "0"} )
     *
     *
     */
    public function index(Request $request,int $parametres)
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($question);

            foreach ($question->getAnswers() as $answer)
            {
                $em->persist($answer);
                $answer->setQuestion($question);
            }


            $em->flush();
            return $this->redirect('/show');
        }

        return $this->render(
            'create/createrOfQuestion.html.twig',
            array('form' => $form->createView(),
                )
        );

    }

    /**
     * @Route("/show/{id}",
     *     defaults={"id" : 1},
     *     )
     */
    public function showing(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository(Question::class);
        return $this->render('create/CreateQuiz.html.twig',
            array ('questions'=> $question->findAll())
        );

    }

    /**
     * @Route("/addquestions",
     *     )
     */

    public function addQuestions(Request $request)
    {
        $quiz = new Quiz();
        $em = $this->getDoctrine()->getManager();
        $questionManager = $em->getRepository(Question::class);
        $quiz->setName($request->query->get("name"));
        foreach ($request->query->get( "id") as $id){

            $quiz->addQuestion($questionManager->find($id));

        }
        $em->persist($quiz);
        $em->flush();
        return $this->redirect('/show');
    }



}
