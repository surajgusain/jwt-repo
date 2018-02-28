<?php
/**
 * Created by PhpStorm.
 * User: SURAJ
 * Date: 24-02-2018
 * Time: 13:30
 */

namespace App\Services;


use App\Exceptions\DataException;
use App\Exceptions\InvalidParameterException;
use App\Repository\UserRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\ORMException;
use function dump;
use Firebase\JWT\JWT;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class FirebaseJwtService
{

    /**
     * @var UserPasswordEncoder
     */
    private $encoder;

    /**
     * @param Request $request
     * @return bool
     * @throws InvalidParameterException
     */
    public function validateRequest(Request $request)
    {
        $type = $request->getContentType();
        $data = json_decode($request->getContent(), true);

        if ($type !== 'json') {
            throw new InvalidTypeException('Type is invalid');
        }

        if (!isset($data['name']) || empty($data['name'])) {
            throw new InvalidParameterException('API Name is needed');
        }

        if (empty($data['params'])) {
            throw new InvalidParameterException('API Parameters are needed');
        }
        return true;
    }

    /**
     * @param UserRepository $repository
     * @param                 $data
     * @return mixed
     * @throws DataException
     * @throws InvalidParameterException
     * @throws ORMException
     */
    public function generateToken(UserRepository $repository, $data)
    {

        $email = $this->validateParameters('email', $data['email'], true);
        $password = $this->validateParameters('password', $data['password'], true);

        $user = $repository->findOneBy(['email' => $email, 'password' => $password]);

        if ($user === null) {
            throw new ORMException("Record not found in our database");
        }

        $payload = [
            'iat' => time(),
            'iss' => 'localhost',
            'exp' => time() + getenv('JWT_EXPIRY_TIME'),
            'userId' => $user->getId()
        ];

        try {
            $token = JWT::encode($payload, getenv('JWT_SECRET_KEY'));
        } catch (\Exception $e) {
            throw new DataException("Error occurred while generating JWT");
        }

        return $token;
    }

    public function processApi()
    {

    }

    public function throwError($code, $message)
    {

    }

    /**
     * @param $fieldName
     * @param $value
     * @param $required
     * @return mixed
     * @throws InvalidParameterException
     */
    public function validateParameters($fieldName, $value, $required)
    {
        if ($required == true && empty($value) == true) {
            throw new InvalidParameterException(ucwords($fieldName)." is required");
        }

        return $value;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws InvalidParameterException
     */
    public function getAuthorizationToken(Request $request)
    {

        $header = $request->headers->get('Authorization');
        $data = explode(' ', $header);

        if(count($data) > 1) {
            $token = $data[1];

            if(strtolower($data[0]) !== 'bearer' || empty($data[1])) {
                throw new InvalidParameterException("Invalid authorization parameters");
            }
            return $token;
        }

        throw new InvalidParameterException("Incorrect number of parameters provided for authentication");
    }
}