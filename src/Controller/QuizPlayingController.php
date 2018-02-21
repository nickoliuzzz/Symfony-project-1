<?php

namespace App\Controller;

use App\Entity\AlreadyPlaying;
use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\Score;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class QuizPlayingController extends Controller
{
    /**
     * @Route("/quiz/playing/{id}", name="quiz_playing")
     * @Security("has_role('ROLE_USER')")
     */
    public function index(int $id)
    {
        $response = $this->checkQuiz($id);
        if($response !== Null){
            return $response;
        }

        $em = $this->getDoctrine()->getManager();
        $quizManager = $em->getRepository(Quiz::class);
        $quiz = $quizManager->find($id);
        $activeUser = $this->get('security.token_storage')->getToken()->getUser();



        $isPlayed = false;
        $alreadyPlayed = new AlreadyPlaying();
        $alreadyPlayeds = $activeUser->getAlreadyplaings();

        foreach ($alreadyPlayeds as $alreadPlay){
            if($alreadPlay->getQuiz()->getId() === $id  )
            {
                $isPlayed = true;
                $alreadyPlayed = $alreadPlay;
                break;
            }
        }

        if($isPlayed === false){
            $alreadyPlayed->setQuiz($quiz);
            $alreadyPlayed->setUser($activeUser);
        }

        $em->persist($alreadyPlayed);
        $em->persist($activeUser);
        $em->flush();

        $question = $quiz->getQuestions()[$alreadyPlayed->getNumberOfAnswers()];

        return $this->render('quiz/OneQuestionOfQuiz.html.twig',
            array('question'=> $question )
        );
    }


    /**
     * @Route("/quiz/answer")
     * @Security("has_role('ROLE_USER')")
     */
    public function answerOnQuestion(Request $request)
    {

        $id = (int) $request->request->get( "idOfQuiz");
        $response = $this->checkQuiz($id);
        if($response !== Null){
            return $response;
        }


        $em = $this->getDoctrine()->getManager();
        $quizManager = $em->getRepository(Quiz::class);
        $quiz = $quizManager->find($id);
        $activeUser = $this->get('security.token_storage')->getToken()->getUser();
        $questions = $quiz->getQuestions();
        $countOfQuestions = count($questions);
        $idOfAnswer =(int) $request->request->get( "idOfAnswer");


        $alreadyPlayed = new AlreadyPlaying();
        $alreadyPlayeds = $activeUser->getAlreadyplaings();
        $idAlreadyPlayed = 0;
        $isPlayed = false;

        foreach ($alreadyPlayeds as $alreadPlay){
            if($alreadPlay->getQuiz()->getId() === $id  )
            {
                $alreadyPlayed = $alreadPlay;
                $idAlreadyPlayed = $alreadPlay->getId();
                $isPlayed = true;
                break;
            }
        }

        $correctAnswers = 0;
        $numberOfQuestion = 0;
        if($isPlayed)
        {
            $numberOfQuestion = $alreadyPlayed->getNumberOfAnswers();
            $correctAnswers = $alreadyPlayed->getNumberOfCorrectAnswers();
        }

        $answer = $em->getRepository(Answer::class)->find($idOfAnswer);
        if($answer->getisTrue()){
            $correctAnswers++;
        }
        var_dump($numberOfQuestion);
        var_dump($countOfQuestions);
        if($numberOfQuestion + 1 == $countOfQuestions)
        {
            $score = new Score();
            $score->setNumberOfCorrectAnswers($correctAnswers);
            $score->setUser($activeUser);
            $score->setQuiz($quiz);
            $em->remove($alreadyPlayed);
            $em->persist($score);
            $em->flush();
            //TODO return response
        }
        else {
            $persistedAlreadyPlayed = $em->getRepository(AlreadyPlaying::class)->find($idAlreadyPlayed);
            $persistedAlreadyPlayed->setNumberOfAnswers($persistedAlreadyPlayed->getNumberOfAnswers() + 1);
            $persistedAlreadyPlayed->setNumberOfCorrectAnswers($correctAnswers);
            //$em->persist($alreadyPlayed);
            $em->flush();
            //TODO return response
        }
        return $this->redirect('/quiz/playing/'.$id);

    }





    public function checkQuiz(int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $quizManager = $em->getRepository(Quiz::class);
        $quiz = $quizManager->find($id);

        if($quiz === Null)
        {
            //TODO return page that said that someone go to quiz that isn't exist
            return new Response("kek");
        }

        if($quiz->getisActive() === false)
        {
            //TODO return page that said that someone go to deactivated quiz
            return new Response("kek");
        }

        $activeUser = $this->get('security.token_storage')->getToken()->getUser();
        $score = new Score();

        $scoresOrderedBy = $em->getRepository(Score::class)->findByQuiz($quiz);
        $scoresOrderedBy = array_reverse($scoresOrderedBy);


        $isInArray = false;
        $numberInArray = 1;

        foreach ($scoresOrderedBy as $scoress)
        {
            if($scoress->getUser() === $activeUser)
            {
                $isInArray = true;
                $score = $scoress;
                break;
            }
            $numberInArray++;
        }

        if($isInArray){
            //TODO page that will show ur result
            $name = $activeUser->getUsername();
            $scorePoints = $score->getNumberOfCorrectAnswers();
            return new Response("Sore, $name, u r already play this quiz.
                                <br> Ur score is $scorePoints and u r $numberInArray in this quiz.");
        }
        return null;
    }





}
