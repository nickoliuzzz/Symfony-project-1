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
//    /**
//     * @Route("/ajax")
//     */
//    public function answerOnQuestion(Request $request)
//    {
//
//        $em = $this->getDoctrine()->getManager();
//        $repository = $em->getRepository(User::class);
//        $user = ["id","email","username"];
//
//        $userJSON = [];
//        $userJSON[] = $user;
//        $array = $request->request->get("arrayOfData");
//
//        $array[0] = (int) $array[0];
//        $array[1] = (int) $array[1];
//        $array[2] = (int) $array[2];
//        $array[3] = (int) $array[3];
//
//        //$users = $repository->sortsortQuery(abs($array[0]),$array[4],0,$array[0]);
//        $users = $repository->findAll();
//        $userJSON[] = $array;
//        //TODO write findByArray (array , $user[0])
//        foreach ($users as $us) {
//            $user  = [];
//            $user[] = $this->json($us->getId());
//            $user[] = $this->json($us->getEmail());
//            $user[] = $this->json($us->getUsername());
//            $userJSON[] = $user;
//        }
//        return $this->json($userJSON);
//    }
//
//    /**
//     * @Route("/ajax1")
//     */
//    public function a1nswerOnQuestion(Request $request)
//    {
//        return $this->render("create/ajaxTry.html.twig");
//    }



}

