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
     * @Route("/question/{id}/edit", name="question_edit")
     */
    public function question(Question $question = null, Request $request, ObjectManager $manager)
    {
        if(!$question){
            $question = new Question();
        }

        $formQuestion = $this->createForm(QuestionType::class, $question);
        $formQuestion->handleRequest($request);

        if ($formQuestion->isSubmitted() && $formQuestion->isValid()){
            $question->setAnswer([$formQuestion->get("answer")->getData()]);
            $question->addQuiz($formQuestion->get("quiz")->getData());

            $manager->persist($question);
            $manager->flush();

            return $this->redirectToRoute('quiz_list');
        }

        return $this->render('question/form.html.twig', [
            'question' => $question,
            'formQuestion' => $formQuestion->createView(),
            'editMode' => $question->getId() !== null
        ]);
    }
}
