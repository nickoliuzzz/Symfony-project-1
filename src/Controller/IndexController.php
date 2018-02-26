<?php

namespace App\Controller;

use App\Entity\AlreadyPlaying;
use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\Score;
use App\Entity\User;
use Doctrine\ORM\Mapping\Id;
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
     * @Route("/users")
     */
    public function adminPageWithUsers(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(User::class);



        $array = $request->request->get("arrayOfData");

        $this->actionWithArrayOfUser($array[5],$array[6]);
        if($array[6] != 0){
            $array[0] = 0;
            $array[1] = 0;
            $array[2] = 0;
            $array[3] =0;
            $array[4] = "";
            $array[5] = [-1];
            $array[6] = (int)$array[6];
        }
        else {
            $array[0] = (int)$array[0];
            $array[1] = (int)$array[1] + $array[2];
            $array[2] = (int)$array[2];
            $array[3] = (int)$array[3];
            $array[6] = 0;
        }

        $user = ["id","email","username"];
        $userJSON = [];
        $userJSON[] = $user;


        $users = $repository->sortQuery($array[0],$array[4],$array[1]);
        $array[3] = (count($repository->findAll()) / 5) -1 ;
        $userJSON[] = $array;


        $buttons = [];
        $buttons[] = ['deleteUsers',"This users will have admin\'s root"];
        $buttons[] = ['makeAdmin','This users will be deleted for always  '];

        $userJSON[] = $buttons;


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
     * @Route("/questions")
     */
    public function adminPageWithQuestions(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Question::class);

        $array = $request->request->get("arrayOfData");
        if($array[6] != 0){
        $this->actionWithArrayOfQuiz($array[5],$array[6]);

            $array[0] = 0;
            $array[1] = 0;
            $array[2] = 0;
            $array[3] = 0;
            $array[4] = "";
            $array[5] = [-1];
            $array[6] = (int)$array[6];
        }
        else {
            $array[0] = (int)$array[0];
            $array[1] = (int)$array[1] + $array[2];
            $array[2] = (int)$array[2];
            $array[3] = (int)$array[3];
            $array[6] = 0;
        }

        $user = ["id","Question","Answers"];
        $userJSON = [];
        $userJSON[] = $user;


        $users = $repository->sortQuery($array[0],$array[4],$array[1]);
        $array[3] = (count($repository->findAll()) / 5) -1 ;
        $userJSON[] = $array;


        $buttons = [];
        $buttons[] = ['deleteQuiz',"This users will have admin\'s root"];
        $buttons[] = ['Create','This users will be deleted for always  '];

        $userJSON[] = $buttons;


        foreach ($users as $us) {
            $user  = [];
            $user[] = $this->json($us->getId());
            $user[] = $this->json($us->getText());
            $answers = $us->getAnswers();
            $text = "";
            foreach ($answers as $answer)
            {
          //      $text =get_class($answer);
                  $text = $text." | ".$answer->getText();
            }

            $user[] = $this->json($text);
            $userJSON[] = $user;
        }
        header('Content-type: application/json');
        return $this->json($userJSON);
       // return json_encode($userJSON);


    }

    /**
     * @Route("/ajax1/{path}")
     */
    public function a1nswerOnQuestion(Request $request,string $path="ajax" )
    {

     //   $this->actionWithArrayOfQuiz([-1,54],1);
        return $this->render("create/ajaxTry.html.twig", array('path'=>$path));
    }


private function actionWithArrayOfQuiz(array $Ids,int $action ){
    $em = $this->getDoctrine()->getManager();
    $repository = $em->getRepository(Question::class);
    $tempArr = array_slice( $Ids,1);
    switch ($action)
    {
        case 1:
            {
                foreach ($tempArr as $tempIdQuestion){
                    $question = $repository->find($tempIdQuestion);
                    foreach($question->getAnswers() as $answer){
                        $em->remove($answer);
                    }
//                    foreach ($question->getQuizzes() as $quiz) {
//                        if(count($quiz->getQuestions()) == 1)
//                        {
//
//                            $scores = $quiz->getScores();
//                            var_dump($scores->slice(0,1));
//                            foreach ($scores as $score){
//
//                                $em->remove($score);
//                            }
//                            $em->remove($quiz);
//                        }
//                   }
                   $em->remove($question);
                }
                $em->flush();
                break;
            }
        case 2:
            {
                var_dump();
            }
    }
}


    private function actionWithArrayOfUser(array $Ids,int $action ){
       $tempArr = array_slice( $Ids,1);

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(User::class);
        switch ($action)
        {
            case 1:
                {
                    foreach ($tempArr as $tempIdUser){
                        $user = $repository->find($tempIdUser);
                        if($user != null) $this->removeUser($user);
                    }
                    $em->flush();
                    break;

                }
            case 2:
                {
                    foreach ($tempArr as $tempIdUser){
                         $user = $repository->find($tempIdUser);
                         if($user != null)
                             $user->setRoles('a:1:{i:0;s:10:"ROLE_ADMIN');
                    }
                    $em->flush();
                }
        }
    }



    public function removeUser(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Score::class);
        $scores = $repository->findByUser($user);
        foreach ($scores as $score){
            $em->remove($score);
        }

        $repository = $em->getRepository(AlreadyPlaying::class);
        $scores = $repository->findByUser($user);
        foreach ($scores as $score){
            $em->remove($score);
        }

        $em->remove($user);
        $em->flush();
    }



}
