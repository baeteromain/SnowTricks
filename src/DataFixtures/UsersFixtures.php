<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for($nbUsers = 1; $nbUsers <= 10; $nbUsers++){
            $user = new User();

            if ($nbUsers === 1){
                $user->setEmail('admin@admin.fr');
                $user->setPassword($this->encoder->encodePassword($user, 'adminadmin'));
                $user->setIsVerified(1);
                $user->setRoles(['ROLE_ADMIN']);
            } else {
            $user->setRoles(['ROLE_USER']);
            $user->setEmail($faker->email);
            $user->setPassword($this->encoder->encodePassword($user, 'azertyazerty'));
            $user->setIsVerified($faker->numberBetween(0,1));
            }
            $user->setUsername($faker->userName);
            $user->setAvatar('Multiavatar-' . $faker->numberBetween(1,4) . '.png');


            $manager->persist($user);

            $this->addReference('user_' . $nbUsers, $user);
        }

        $manager->flush();
    }
}
