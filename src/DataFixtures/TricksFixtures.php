<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class TricksFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $allTricksName = ['Mute', 'Indy', '360', '720', 'Backflip', 'Misty', 'Tail slide', 'Method air', 'Backside air'];
        $allTricksVideos = [
            'Mute' => 'https://www.youtube.com/watch?v=k6aOWf0LDcQ',
            'Indy'=> 'https://www.youtube.com/watch?v=6yA3XqjTh_w',
            '360'=> 'https://www.youtube.com/watch?v=GS9MMT_bNn8',
            '720'=>'https://www.youtube.com/watch?v=1vtZXU15e38',
            'Backflip'=> 'https://www.youtube.com/watch?v=SlhGVnFPTDE',
            'Misty'=> 'https://www.youtube.com/watch?v=hPuVJkw1MmI',
            'Tail slide'=> 'https://www.youtube.com/watch?v=KP6_2qtXlb8',
            'Method air'=> 'https://www.youtube.com/watch?v=_hxLS2ErMiY',
            'Backside air'=> 'https://www.youtube.com/watch?v=_CN_yyEn78M'
        ];

        foreach ($allTricksName as $trickName) {
            $user = $this->getReference('user_' . $faker->numberBetween(1, 10));
            $category = $this->getReference('category_' . $faker->numberBetween(1, 7));

            $trick = new Trick();
            $trick->setUser($user);
            $trick->setCategory($category);
            $trick->setTitle($trickName);
            $trick->setDescription($faker->realText(400));
            $trick->setCreatedAt($faker->dateTime('now', 'Europe/Paris'));
            $trick->setUpdatedAt(null);
            $trick->setSlug($faker->slug);

            $videoTrick = new Video();
            $videoTrick->setUser($this->getReference('user_1'));
            foreach ($allTricksVideos as $name => $trickVideo){
                if ($trickName === $name){
                    $videoTrick->setEmbed($trickVideo);
                    $trick->addVideo($videoTrick);
                }
            }



            for ($image = 1; $image <= 3; $image++) {
                $imageTrick = new Image();
                $imageTrick->setName($trickName . ' ' . $faker->numberBetween(1, 3));
                $imageTrick->setUser($this->getReference('user_1'));
                $trick->addImage($imageTrick);

                $manager->persist($trick);
            }
            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
            CategoriesFixtures::class,
            UsersFixtures::class
        ];
    }
}
