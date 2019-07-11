<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Form\QuestionType;
use App\Repository\QuizRepository;
use App\Repository\ReponseRepository;
use App\Repository\ResultRepository;
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

    /**
     * @Route("/quiz/{id_quiz}/question/{id}/delete", name="question_delete")
     */
    public function delete($id_quiz, Question $question, ObjectManager $manager, QuizRepository $quizRepository,
    ReponseRepository $reponseRepository, ResultRepository $resultRepository){
        $quiz = $quizRepository->find($id_quiz);

        $reponses = $reponseRepository->findBy(['question' => $question]);
        foreach ($reponses as $reponse){
            $result = $resultRepository->findOneBy(['user' => $reponse->getUser(), 'quiz' => $quiz]);
            if ($reponse->getReponse() == $question->getAnswer()[0]){
                $result->setResultat($result->getResultat()-1);
                $manager->persist($result);
            }
            $manager->remove($reponse);
        }
        $quiz->removeQuestion($question);
        $manager->persist($quiz);

        $manager->flush();
        return $this->redirectToRoute('quiz_questions', ['quizID' => $id_quiz]);
    }
}
