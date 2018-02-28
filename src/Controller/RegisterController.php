<?php
/**
 * Created by PhpStorm.
 * User: Suraj Gusain
 * Date: 27/2/18
 * Time: 10:54 AM
 */

namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends Controller
{

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $encoder)
    {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $user->setPassword($encoder->encodePassword($user, $user->getPlainPassword()));
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('login');
        }

        return $this->render('login/register.html.twig', array('form' => $form->createView()));
    }

}