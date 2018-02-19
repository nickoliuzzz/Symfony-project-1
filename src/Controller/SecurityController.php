<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Form\UserType;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends Controller
{
    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'security/registration.html.twig',
            array('form' => $form->createView())
        );
    }
    /**
     * @Route("/user_edit/{id}", name="user_edit")
     *
     */
    public function editAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 3) Encode the password (you could also do this via Doctrine listener)
        $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);

        // 4) save the User
        $em->flush();
        // ... do any other work - like sending them an email, etc
        // maybe set a "flash" success message for the user

        return $this->redirectToRoute('homepage');
    }

        return $this->render(
            'security/registration.html.twig',
            array('form' => $form->createView())
        );
    }
    /**
     * @Route("/user_delete", name="user_delete"):void
     */
    public function user_delete_Action(Request $request)
    {
        //$user_id= array( $request->query->get("id"));
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class);
        foreach ($request->query->get("id") as $id){
           $em->remove($user->find($id));
        }
        $em->flush();
        return $this->redirectToRoute("homepage");
    }
}