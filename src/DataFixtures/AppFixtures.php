<?php

namespace App\DataFixtures;

use App\Entity\TimeMachine;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $timeMachine = new TimeMachine();
        $timeMachine->setName('DeLorean');
        $timeMachine->setResourceUrl('https://en.wikipedia.org/wiki/DeLorean_DMC-12');

        $manager->persist($timeMachine);

        $timeMachine = new TimeMachine();
        $timeMachine->setName('TARDIS');
        $timeMachine->setResourceUrl('https://en.wikipedia.org/wiki/TARDIS');

        $manager->persist($timeMachine);

        $timeMachine = new TimeMachine();
        $timeMachine->setName('Time Machine');
        $timeMachine->setResourceUrl('https://www.youtube.com/watch?v=8zwEnNJumQ4');

        $manager->persist($timeMachine);

        $timeMachine = new TimeMachine();
        $timeMachine->setName('Kill Hitler');
        $timeMachine->setResourceUrl('https://xkcd.com/1063/');

        $manager->persist($timeMachine);
        $manager->flush();
    }
}