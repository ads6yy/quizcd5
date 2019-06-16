<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\Result;
use App\Form\QuizAnswerType;
use App\Form\QuizType;
use App\Repository\QuizRepository;
use App\Repository\ResultRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
    public function list(QuizRepository $quizRepository){

        $quiz = $quizRepository->findAll();
        $userResult = $this->getUser()->getResults();

        $results = [];

        foreach ($quiz as $quizz){
            foreach ($userResult as $result){
                if ($quizz == $result->getQuiz()){
                    $count = count($quizz->getQuestions());
                    $results[$quizz->getId()] = $result->getResultat().'/'.$count;
                }
            }
        }

        return $this->render('quiz/list.html.twig', [
            'quiz' => $quiz,
            'results' => $results
        ]);
    }

    /**
     * @Route("/quiz/{id}", name="quiz_show")
     */
    public function show($id, QuizRepository $repository, ResultRepository $resultRepository, Request $request, ObjectManager $manager){
        $quiz = $repository->find($id);
        $questions = $quiz->getQuestions();
        $user = $this->getUser();

        $form = $this->createFormBuilder();
        $i=1;
        foreach ($questions as $question){
            $form->add($i, TextType::class);
            $i++;
        }
        $form_questions = $form->getForm();

        $form_questions->handleRequest($request);
        if ($form_questions->isSubmitted() && $form_questions->isValid()){
            $resultat = 0;
            $answers = $form_questions->getData();

            for($k = 0; $k < count($answers); $k++){
                if ($answers[$k+1] == $questions[$k]->getAnswer()[0]){
                    $resultat++;
                }
            }
            $result = $resultRepository->findOneBy(['quiz' => $quiz->getId()]);
            if ($result){
                $result->setResultat($resultat);
            }
            else{
                $result = new Result();
                $result->setQuiz($quiz);
                $result->setUser($user);
                $result->setResultat($resultat);
            }

            $manager->persist($result);
            $manager->flush();

            return $this->redirectToRoute('quiz_list');
        }

        return $this->render('quiz/show.html.twig', [
            'quiz' => $quiz,
            'questions' => $questions,
            'form' => $form_questions->createView(),
            'answer' => 'answer'
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
