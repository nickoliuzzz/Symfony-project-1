<?php

namespace App\Controller;

use App\Entity\Question;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\UserType;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class VictoryController extends Controller
{
    /**
     * @Route("/Vic", name="Victory")
     */
    public function ViweQuest(Request $request)
    {
        $em= $this->getDoctrine()->getManager();
        $quest= $em->getRepository(Question::class);


        return $this->render("security/edituser.html.twig",array("question"=>$quest->findAll()));
    }
}