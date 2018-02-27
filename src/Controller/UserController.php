<?php
/**
 * Created by PhpStorm.
 * User: SURAJ
 * Date: 24-02-2018
 * Time: 13:34
 */

namespace App\Controller;


use App\Repository\UsersRepository;
use App\Services\FirebaseJwtService;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class UserController extends Controller
{

    /**
     * UserController constructor.
     *
     * @param UsersRepository $repository
     */
    private $repository;

    /**
     * @var FirebaseJwtService
     */
    private $jwtService;

    public function __construct(UsersRepository $repository, FirebaseJwtService $jwtService)
    {
        $this->repository = $repository;
        $this->jwtService = $jwtService;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \App\Exceptions\InvalidParameterException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addUser(Request $request)
    {

        $token = $this->jwtService->getAuthorizationToken($request);
        $payload = JWT::decode($token, getenv('JWT_SECRET_KEY'), ['HS256']);

        $user = json_decode($request->getContent(), true);
        $user = $this->repository->addUser($user['param']);

        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
        $response = $serializer->serialize($user, 'json');

        return new Response($response);
    }

    public function logout()
    {

    }
    
}