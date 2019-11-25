<?php


namespace App\Controller;


use App\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends BaseController
{
    /**
     * @Route("/api/getUserData", methods={"POST"})
     *
     */
    public function getUserData()
    {
        $user['email'] = $this->getUser()->getEmail();
        $user['city'] = $this->getUser()->getUserData()->getCity();
        $user['postcode'] = $this->getUser()->getUserData()->getPostalcode();
        $user['street'] = $this->getUser()->getUserData()->getStreet();
        $user['street_number'] = $this->getUser()->getUserData()->getStreetNumber();
        $user['birthday'] = $this->getUser()->getUserData()->getDateOfBirth();
        $user['first_name'] = $this->getUser()->getUserData()->getFirstname();
        $user['last_name'] = $this->getUser()->getUserData()->getLastname();

        return $this->json($user, 200, []);
    }

    /**
     * @Route("/api/updateUserData", methods={"PUT"})
     */
    public function updateUser()
    {
        return $this->json('Update', 200, []);
    }
}