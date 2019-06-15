<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Form\QuizAnswerType;
use App\Form\QuizType;
use App\Repository\QuizRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    /**
     * @Route("/", name="quiz_home")
     */
    public function index()
    {
        return $this->render('quiz/index.html.twig', [
        ]);
    }

    /**
     * @Route("/quiz", name="quiz_list")
     */
    public function list(QuizRepository $repository){

        $quiz = $repository->findAll();

        return $this->render('quiz/list.html.twig', [
            'quiz' => $quiz
        ]);
    }

    /**
     * @Route("/quiz/{id}", name="quiz_show")
     */
    public function show($id, QuizRepository $repository){
        $quiz = $repository->find($id);
        $questions = $quiz->getQuestions();

        $q = new Question();
        $form = $this->createForm(QuizAnswerType::class, $q);

//        $numQuestion = 0;
//        $question = $questions[$numQuestion];

        return $this->render('quiz/show.html.twig', [
            'quiz' => $quiz,
            'questions' => $questions,
            'formObject' => $form
        ]);
    }

    /**
     * @Route("/quiz_add", name="quiz_add")
     */
    public function add(Request $request, ObjectManager $manager){
        $quiz = new Quiz();
        $formQuiz = $this->createForm(QuizType::class, $quiz);
        $formQuiz->handleRequest($request);

        if ($formQuiz->isSubmitted() && $formQuiz->isValid()){
            $manager->persist($quiz);
            $manager->flush();

            return $this->redirectToRoute('quiz_list');
        }

        return $this->render('quiz/add.html.twig', [
            'formQuiz' => $formQuiz->createView(),
        ]);
    }
}
