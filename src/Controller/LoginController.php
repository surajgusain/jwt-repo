<?php
/**
 * Created by PhpStorm.
 * User: Suraj Gusain
 * Date: 27/2/18
 * Time: 10:44 AM
 */

namespace App\Controller;


use App\Form\LoginForm;
use function dump;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
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
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $this->createForm(LoginForm::class, [
            'username' => $lastUsername,
        ]);

        return $this->render('login/login.html.twig', array(
            'form' => $form->createView(),
            'error' => $error,
        ));
    }

    public function loginRequest(Request $request)
    {
        dump($request->getContent());
        die();
    }

    /**
     * @Route("logout", name="logout")
     */
    public function logout()
    {

    }

}