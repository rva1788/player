<?php

namespace App\DataFixtures;

use App\Entity\Player;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $countries = [
            "Ukraine",
            "Bulgaria",
            "Poland",
            "Germany",
        ];

        for ($i=0; $i < 120; $i++) {
            $manager->persist((new Player())
                ->setCountry($countries[rand(0,3)])
                ->setBirthDate(new \DateTime(rand(1,30) . "." . rand(1,12) . "." . rand(1980,2020)))
                ->setName("Player" . rand(0, 999))
                ->setPosition(Player::POSITIONS[rand(0,3)])
            );
        }

        $manager->flush();
    }
}
