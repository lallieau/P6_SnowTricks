<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // USER FIXTURES
        $user = new User();
        $user
            ->setUsername('Lola')
            ->setEmail('lola@gmail.com');
        $password = $this->encoder->encodePassword($user, 'pass_lola');
        $user->setPassword($password);

        $manager->persist($user);


        $user1 = new User();
        $user1
            ->setUsername('Pierre')
            ->setEmail('pierre@gmail.com');
        $password = $this->encoder->encodePassword($user1, 'pass_pierre');
        $user1->setPassword($password);

        $manager->persist($user1);


        $user2 = new User();
        $user2
            ->setUsername('Camille')
            ->setEmail('camille@gmail.com');
        $password = $this->encoder->encodePassword($user2, 'pass_camille');
        $user2->setPassword($password);

        $manager->persist($user2);

        // TRICKS FIXTURES

        $manager->flush();
    }
}
