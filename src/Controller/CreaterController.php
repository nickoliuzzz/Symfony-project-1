<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Form\AnswerType;
use App\Form\QuestionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreaterController extends Controller
{
    /**
     * @Route("/createquestion", name="createrquestion")
     */
    public function index(Request $request)
    {
        $question = new Question();




        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);




        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($question);

            $question->setIsActive(true);
            foreach ($question->getAnswers() as $answer){

                $em->persist($answer);
                $answer->setQuestion($question);


            }


            $em->flush();

       //     return $this->redirectToRoute('user_registration');
        }

        return $this->render(
            'create/createrOfQuestion.html.twig',
            array('form' => $form->createView(),
                )
        );

    }

    /**
     * @Route("/show/{id}", name="show")
     */
    public function showing(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository(Question::class);
        return $this->render('create/CreateQuiz.html.twig',
            array ('questions'=> $question->findAll())
        );

    }




}
