<?php
/**
 * Created by PhpStorm.
 * User: nickolka
 * Date: 23.2.18
 * Time: 18.48
 */

namespace App\Controller;

use App\Entity\Question;
use Proxies\__CG__\App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class asd extends Controller
{
    /**
     * @Route("/kek")
     */
    public function answerOnQuestion(Request $request)
    {
        $idOfAnswer = (int)$request->request->get("arrayOfdata");
        $em = $this->getDoctrine()->getManager();
        $questions = $em->getRepository(User::class)->findAll();
        $quesion = null;
        $que = null;

        $quesion = ["id","email","username"];

        $que[] = $quesion;

        foreach ($questions as $question){
            $quesion = [];
            $quesion[] =  $this->json($question->getId());
            $quesion[] =  $this->json($question->getEmail());
            $quesion[] =  $this->json($question->getUsername());
            $que[] = $quesion;
        }

        return $this->json($que);
    }

    /**
     * @Route("/kek1")
     */
    public function a1nswerOnQuestion(Request $request)
    {
        return $this->render("create/kek.html.twig");
    }


}

