<?php

namespace App\Controller;

use App\Repository\QuizRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
