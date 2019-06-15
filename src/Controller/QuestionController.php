<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/question_add", name="question_add")
     */
    public function add(Request $request, ObjectManager $manager)
    {
        $question = new Question();
        $formQuestion = $this->createForm(QuestionType::class, $question);
        $formQuestion->handleRequest($request);

        if ($formQuestion->isSubmitted() && $formQuestion->isValid()){
            $question->setAnswer([$formQuestion->get("answer")->getData()]);
            $question->addQuiz($formQuestion->get("quiz")->getData());

            $manager->persist($question);
            $manager->flush();

            return $this->redirectToRoute('quiz_list');
        }

        return $this->render('question/add.html.twig', [
            'formQuestion' => $formQuestion->createView(),
        ]);
    }
}
