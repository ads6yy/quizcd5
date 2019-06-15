<?php

namespace App\DataFixtures;

use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class QuestionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++){
            $question = new Question();
            $question->setType('simple');
            $question->setQuestion('Question '.$i);
            $question->setAnswer(['Réponse '.$i]);

            $manager->persist($question);
        }

        $manager->flush();
    }
}
