<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class LoginFormAuthenticator extends AbstractGuardAuthenticator
{
    private $userRepository;
    private $passwordEncoder;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * LoginFormAuthenticator constructor.
     *
     * @param UserRepository               $userRepository
     * @param RouterInterface              $router
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserRepository $userRepository,RouterInterface $router,UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->router = $router;
    }


    public function supports(Request $request)
    {
        if(!$request->isMethod('POST')){
            return new JsonResponse([
                'message' => 'Please use POST'
            ], Response::HTTP_FORBIDDEN);
        }
        return true;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
    }

    public function getCredentials(Request $request)
    {

        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password')
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {

        return $this->userRepository->findOneBy(['email' => $credentials['email']]);
    }


    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
//        return true;
    }


    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'message' => $exception->getMessageKey()
        ], Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }


    public function supportsRememberMe()
    {
       return false;
    }


}
