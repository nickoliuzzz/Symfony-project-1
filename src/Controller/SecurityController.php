<?php

namespace App\Controller;

use App\Entity\VerificationEmail;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
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
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $verificatedSting = $this->random_str("alphanum", 64);
            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $verification = new VerificationEmail();
            $verification->setUser($user);
            $verification->setVerificationString($verificatedSting);

            $this->sendEmail($verificatedSting, $user, $mailer, 'verif');
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->persist($verification);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'security/registration.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/email_verification/{id}", name="email_verif")
     *
     */
    public function emailVerification($id)
    {
        $em = $this->getDoctrine()->getManager();
        $email = $em->getRepository(VerificationEmail::class)->findOneBy(['verificationString' => $id]);
        if (!$email->getForgot()) {
            $userEmail = $email->getUser();
            $em->getRepository(User::class)->find($userEmail)->setRoles(array("ROLE_USER"));
            $em->remove($email);
            $em->flush();
        }
        return $this->redirectToRoute("homepage");
    }

    /**
     * @Route("/user_edit/{id}", name="user_edit")
     * @Security("has_role('ROLE_ADMIN')")
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
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        return $this->render(
            'security/registration.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/user_delete", name="user_delete")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function user_delete_Action(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class);
        foreach ($request->query->get("id") as $id) {
            $em->remove($user->find($id));
        }
        $em->flush();
        return $this->redirectToRoute("homepage");
    }

    /**
     * @Route("/forgot", name="forgot")
     */
    public function forgotPassword(Request $request, \Swift_Mailer $mailer)
    {
        $email = $request->query->get("fp_email");
        if ($email) {
            $verificatedSting = $this->random_str("alphanum", 64);

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

            $verification = new VerificationEmail();

            $verification->setUser($user);
            $verification->setVerificationString($verificatedSting);
            $verification->setForgot(true);

            $em->persist($verification);
            $em->flush();

            $this->sendEmail($verificatedSting, $user, $mailer, 'forgot');
        }
        return $this->render('security/forgot.html.twig');
    }

    /**
     * @Route("/forgot_passowrd/{id}")
     */
    public function forgotPasswordAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $email = $em->getRepository(VerificationEmail::class)->findOneBy(['verificationString' => $id]);

        if ($email->getForgot()) {
            $user = $em->getRepository(User::class)->find($email->getUser());
            $form = $this->createFormBuilder($user)->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))->getForm();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // 3) Encode the password (you could also do this via Doctrine listener)
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
                $em->remove($email);
                $em->flush();
                return $this->redirectToRoute('homepage');
            }
        }
        return $this->render("security/forgotedit.html.twig",
            array('form' => $form->createView()));
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

    public function sendEmail(string $str, User $user, \Swift_Mailer $mailer, $type): void
    {
        switch ($type) {

            case 'verif':
                {
                    $message = (new \Swift_Message('Verification user'))
                        ->setFrom('itrasymfony@gmail.com')
                        ->setTo($user->getEmail())
                        ->setBody(
                            new Response("http:/symfony.dev/email_verification/" . $str
                            ),
                            'text/html'
                        );
                    break;
                }
            case 'forgot':
                {
                    $message = (new \Swift_Message('Forgot password'))
                        ->setFrom('itrasymfony@gmail.com')
                        ->setTo($user->getEmail())
                        ->setBody(
                            new Response("http:/symfony.dev/forgot_passowrd/" . $str
                            ),
                            'text/html'
                        );
                    break;
                }
        }
        $mailer->send($message);
        return;
    }
}