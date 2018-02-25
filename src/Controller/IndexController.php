<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Quiz;
use App\Entity\Score;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class IndexController extends Controller
{
    /**
     * @Route("/{id}",name="homepage", requirements={"id"="\d+"})
     *
     */
    public function IndexPage(Request $request,$id=1)
    {
        $em = $this->getDoctrine()->getManager();
        $count = $em->getRepository(Quiz::class)->getNumberofQuiz();
        if($id<1)
        {
            return $this->redirect("/1");
        }
        else{
            if($count/5<$id)
            {
                return $this->redirect("/".$count/5);
            }
        }
        $users = $em->getRepository(User::class);
        $quizzes = $em->getRepository(Quiz::class)->findBySomething(($id-1)*5);
        $cortage[] = null;
        foreach ($quizzes as $quiz) {
            $cortage[] = $em->getRepository(Score::class)->findByQuiz($quiz);
        }
        return $this->render("index.html.twig",array('users'=>$users->findAll(),
            'quiz'=>$quizzes,
            'topuser'=>$cortage,
            'countpage'=>$count,
            ));
    }
    /**
     * @Route("/profile",name="profile")
     *
     */
    public function proflePage(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class);
        return $this->render("Profile/ProfilePage.html.twig",array('users'=>$users->findAll()));
    }
    /**
     * @Route("/login",name="login")
     */
    public function userLogin(Request $request, AuthenticationUtils $authUtils)
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }
    /**
     * @Route("/kek",name="kek")
     */
    public function kek(Request $request):void
    {
        $em= $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->sortQuery(-1,'1',0);
        var_dump($users);
        return;
    }

    /**
     * @Route("/ajax")
     */
    public function answerOnQuestion(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(User::class);
        $user = ["id","email","username"];
        $userJSON = [];
        $userJSON[] = $user;


        $array = $request->request->get("arrayOfData");

        $array[0] = (int) $array[0];
        $array[1] = (int) $array[1] + $array[2];
        $array[2] = (int) $array[2];
        $array[3] = (int) $array[3];

        $users = $repository->sortQuery(abs($array[0]),$array[4],$array[1]);
        $array[3] = count($repository->findAll());



        $userJSON[] = $array;
        foreach ($users as $us) {
            $user  = [];
            $user[] = $this->json($us->getId());
            $user[] = $this->json($us->getEmail());
            $user[] = $this->json($us->getUsername());
            $userJSON[] = $user;
        }
          return $this->json($userJSON);
    }

    /**
     * @Route("/ajax1")
     */
    public function a1nswerOnQuestion(Request $request)
    {
        return $this->render("create/ajaxTry.html.twig");
    }





}
