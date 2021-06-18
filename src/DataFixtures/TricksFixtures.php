<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TricksFixtures extends Fixture
{


    public function __construct(UserPasswordEncoderInterface $encoder)
    {

    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $allTricksName = ['Mute', 'Indy', '360', '720', 'Backflip', 'Misty', 'Tail slide', 'Method air', 'Backside air'];

        foreach ($allTricksName as $trickName){
            $user = $this->getReference('user_' . $faker->numberBetween(1,10));
            $category = $this->getReference('category_' . $faker->numberBetween(1,7));

            $trick = new Trick();
            $trick->setUser($user);
            $trick->setCategory($category);
            $trick->setTitle($trickName);
            $trick->setDescription($faker->realText(400));
            $trick->setCreatedAt($faker->dateTime('now', 'Europe/Paris'));
            $trick->setUpdatedAt(new \DateTime());
            $trick->setSlug($faker->slug);



        }

        $manager->flush();
    }
}
