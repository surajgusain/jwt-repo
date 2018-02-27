<?php
/**
 * Created by PhpStorm.
 * User: SURAJ
 * Date: 24-02-2018
 * Time: 13:15
 */

namespace App\Controller;


use App\Exceptions\InvalidParameterException;
use App\Repository\UsersRepository;
use App\Services\FirebaseJwtService;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class HomeController extends Controller
{

    /**
     * @var FirebaseJwtService
     */
    private $jwtService;

    /**
     * @var UsersRepository
     */
    private $userRepository;

    public function __construct(FirebaseJwtService $jwtService, UsersRepository $userRepository)
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

        if($this->jwtService->validateRequest($request)) {

            $token = $this->jwtService->generateToken($this->userRepository, $data['param']);

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

}