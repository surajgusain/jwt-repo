<?php
/**
 * Created by PhpStorm.
 * User: SURAJ
 * Date: 24-02-2018
 * Time: 13:15
 */

namespace App\Controller;


use App\Exceptions\InvalidParameterException;
use App\Repository\UserRepository;
use App\Services\FirebaseJwtService;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{

    /**
     * @var FirebaseJwtService
     */
    private $jwtService;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(FirebaseJwtService $jwtService, UserRepository $userRepository)
    {
        $this->jwtService = $jwtService;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        return $this->render('index.html.twig');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws InvalidParameterException
     * @throws ORMException
     * @throws \App\Exceptions\DataException
     */
    public function token(Request $request)
    {

        $token = null;
        $data = json_decode($request->getContent(), true);


        if ($this->jwtService->validateRequest($request)) {

            $token = $this->jwtService->generateToken($this->userRepository, $data['params']);

            return new JsonResponse(['token' => $token], 200);
        }

        return new JsonResponse("Invalid request", 400);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws InvalidParameterException
     */
    public function test(Request $request)
    {

        $response = $this->jwtService->getAuthorizationToken($request);
        return new Response($response);
    }

    public function page(Request $request)
    {
        return $this->render('login/page.html.twig', array());
    }

    /**
     * @Route("/sso", name="hello")
     * @param Request $request
     * @return Response
     */
    public function hello(Request $request)
    {
        return new Response('Hello, you are in sso page!');
    }

    /**
     * @Route("/admin", name="admin_page")
     * @param Request $request
     * @return Response
     */
    public function adminIndex(Request $request)
    {
        return new Response('Hello! You are in admin page');
    }

}