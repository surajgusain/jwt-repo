<?php
/**
 * Created by PhpStorm.
 * User: Suraj Gusain
 * Date: 27/2/18
 * Time: 10:44 AM
 */

namespace App\Controller;


use function dump;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends Controller
{

    /**
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {

        $errors = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/login.html.twig', array(
            'error' => $errors,
            'username' => $lastUsername
        ));
    }

    public function logoutAction()
    {

    }

}