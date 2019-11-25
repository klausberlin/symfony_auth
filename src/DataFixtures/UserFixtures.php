<?php

namespace App\DataFixtures;

//use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\ApiToken;
use App\Entity\User;
use App\Entity\UserData;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends BaseFixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $this->createMany(10,'main_users',function($i) use ($manager){
            $user = new User();
            $user->setEmail(sprintf('spacebar%d@example.de',$i));
            $user->setFirstname($this->faker->firstName);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'engage'
            ));
            $user->setUserData(new UserData());
            $userData = $user->getUserData();
            $userData->setFirstname($this->faker->firstName);
            $userData->setLastname($this->faker->lastName);
            $userData->setCity($this->faker->city);
            $userData->setPostalcode($this->faker->postcode);
            $userData->setStreet($this->faker->streetAddress);
            $userData->setCity($this->faker->city);
            $userData->setStreetNumber($this->faker->buildingNumber);
            $userData->setDateOfBirth($this->faker->dateTime);
            $apiToken1 = new ApiToken($user);
            $manager->persist($apiToken1);
            return $user;
        });
        $manager->flush();
    }
}
