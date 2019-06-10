<?php

namespace App\DataFixtures;

use App\Entity\Quiz;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class QuizFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i=0; $i<3; $i++){
            $quiz = new Quiz();
            $quiz->setTitle('Quiz'.$i);
            $manager->persist($quiz);
        }

        $manager->flush();
    }
}
