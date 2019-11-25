<?php

namespace App\Controller;

use App\Entity\ApiToken;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * @param Request                $request
     * @param EntityManagerInterface $em
     *
     * @return JsonResponse
     */
    public function login(Request $request, EntityManagerInterface $em)
    {
        $user = $em->getRepository('App:User')->findOneBy(['email' => $request->request->get('email')]);
        $apiToken = new ApiToken($user);
        $em->persist($apiToken);
        $em->flush();
        return new JsonResponse(['token' => $apiToken->getToken()]);
    }

    /**
     * @Route("/api", name="app_homepage")
     */
    public function index()
    {
        return $this->json([
            'message' => 'homepage!',
            'path' => 'src/Controller/SecurityController.php',
        ]);
    }

    /**
     * @Route("/register", name="app_register")
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        //todo define user object which data we want to save in the database
        if($request->isMethod('POST')){
//            return $this->json([
//                'message' => 'register!'
//            ]);
            $user = new User();
            $user->setFirstname($request->request->get('name'));
            $user->setEmail($request->request->get('email'));
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $request->request->get('password')
            ));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }
    }


}
