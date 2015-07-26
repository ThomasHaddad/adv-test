<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\RegisterType;
use AppBundle\Form\ResetPasswordType;
use AppBundle\Form\NewPasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Util\SecureRandom;

class UserController extends Controller
{
    /**
     * @Route("/inscription", name="register")
     */
    public function registerAction(Request $request)
    {
        $user=new User();
        $registerForm= $this->createForm(new RegisterType(),$user);

        $registerForm->handleRequest($request);

        if($registerForm->isValid()){

            $user->setDateRegistered(new \DateTime());
            $user->setDateModified(new \DateTime());
            $user->setRoles(array("ROLE_ADMIN"));

            $generator= new SecureRandom();
            $salt= bin2hex($generator->nextBytes(30));
            $token= bin2hex($generator->nextBytes(30));
            $user->setSalt($salt);
            $user->setToken($token);

            $encoder=$this->get('security.password_encoder');
            $encoded= $encoder->encodePassword($user, $user->getPlainPassword());

            $user->setPassword($encoded);
            dump($user);
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

        }

        $params=[
           'registerForm'=>$registerForm->createView()
        ];
        return $this->render('user/register.html.twig',$params);
    }

    /**
     * @Route("/connexion", name="login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'user/login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction()
    {
        // this controller will not be executed,
        // as the route is handled by the Security system
    }

    /**
     * @Route("/deconnexion", name="logout")
     */
    public function logoutAction(){

    }

}
