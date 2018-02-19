<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    /**
     * @Route("/",name="homepage")
     */
    public function IndexPage(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class);
        return $this->render("index.html.twig",array('users'=>$users->findAll()));
    }

    /**
     * @Route("/email",name="email")
     */
    public function emailPage(Request $request,\Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('itrasymfony@gmail.com')
            ->setTo('iviknick@gmail.com')
            ->setBody(
                $this->renderView("base.html.twig"
                ),
                'text/html'
            );
        $mailer->send($message);
        return new Response("hello");
    }
    /**
     * @Route("/addquiz",name="addquiz")
     */
    public function addquiz(Request $request)
    {
        return $this->render("quiz/addquiz.html.twig");
    }
    /**
     * @Route("/user/{id}",name="user_del")
     */
    public function user_del(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class);
        //var_dump($users);
        //$em= $this->getDoctrine()->getRepository(User::class);

        return $this->render("security/user_del.html.twig",array('users'=>$users->findBy(['id'=>$id])));
    }

}
