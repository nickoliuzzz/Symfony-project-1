<?php

namespace App\Controller;

use App\Entity\VerificationEmail;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Form\UserType;
use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends Controller
{
    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer)
    {
        // 1) build the form
        $verificatedSting = $this->random_str("alphanum", 64);
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            //random str to verification user
            $verification = new VerificationEmail();
            $verification->setUser($user);
            $verification->setVerificationString($verificatedSting);

            $this->sendEmail($verificatedSting, $user, $mailer);
            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->persist($verification);
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
     * @Route("/email_verification/{id}", name="verification")
     *
     */
    public function emailVerification(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $email = $em->getRepository(VerificationEmail::class)->findOneBy(['verificationString' => $id]);
        $userEmail = $email->getUser();
        $em->getRepository(User::class)->find($userEmail)->setRoles(array("ROLE_USER"));
        $em->remove($email);

        $em->flush();
        return $this->redirectToRoute("homepage");
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
                'No product found for id ' . $id
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
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function user_delete_Action(Request $request,AuthorizationCheckerInterface $authChecker)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class);
        foreach ($request->query->get("id") as $id) {
            $em->remove($user->find($id));
        }
        $em->flush();
        return $this->redirectToRoute("homepage");
    }

    function random_str($type = 'alphanum', $length = 8)
    {
        switch ($type) {
            case 'basic'    :
                return mt_rand();
                break;
            case 'alpha'    :
            case 'alphanum' :
            case 'num'      :
            case 'nozero'   :
                $seedings = array();
                $seedings['alpha'] = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $seedings['alphanum'] = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $seedings['num'] = '0123456789';
                $seedings['nozero'] = '123456789';

                $pool = $seedings[$type];

                $str = '';
                for ($i = 0; $i < $length; $i++) {
                    $str .= substr($pool, mt_rand(0, strlen($pool) - 1), 1);
                }
                return $str;
                break;
            case 'unique'   :
            case 'md5'      :
                return md5(uniqid(mt_rand()));
                break;
        }
    }

    public function sendEmail(string $str, User $user, \Swift_Mailer $mailer): void
    {
        $message = (new \Swift_Message('Verification user'))
            ->setFrom('itrasymfony@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                new Response("http:/symfony.dev/email_verification/" . $str
                ),
                'text/html'
            );
        $mailer->send($message);
        return;
    }
}