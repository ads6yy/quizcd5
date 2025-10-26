<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\Reponse;
use App\Entity\Result;
use App\Form\QuizAnswerType;
use App\Form\QuizType;
use App\Repository\QuestionRepository;
use App\Repository\QuizRepository;
use App\Repository\ReponseRepository;
use App\Repository\ResultRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    /**
     * @Route("/", name="quiz_home")
     */
    public function index(UserRepository $userRepository, QuizRepository $quizRepository)
    {
        $quiz = $quizRepository->findAll();
        $lastUsers = $userRepository->findBy(array(), array('createdAt' => 'desc'));

        return $this->render('quiz/index.html.twig', [
            'lastUsers' => $lastUsers,
            'quiz' => $quiz
        ]);
    }

    /**
     * @Route("/quiz", name="quiz_list")
     */
    public function list(QuizRepository $quizRepository){
        $results = [];
        $quiz = $quizRepository->findAll();
        if ($this->getUser()){
            $userResult = $this->getUser()->getResults();
            foreach ($quiz as $quizz){
                foreach ($userResult as $result){
                    if ($quizz == $result->getQuiz()){
                        $count = count($quizz->getQuestions());
                        $results[$quizz->getId()] = $result->getResultat().' / '.$count;
                    }
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
    public function show($id, QuizRepository $repository, ResultRepository $resultRepository, ReponseRepository $reponseRepository, Request $request, EntityManagerInterface $manager){
        $quiz = $repository->find($id);
        $questions = $quiz->getQuestions();
        $user = $this->getUser();
        $reponses = [];

        $form = $this->createFormBuilder();
        $i=1;
        foreach ($questions as $question){
            $form->add($i, TextType::class, ['label' => false]);
            $existingReponse = $reponseRepository->findBy(['user' => $user->getId(), 'question' => $question->getId()]);
            if ($existingReponse){
                $goodAnswer = $question->getAnswer()[0];
                if ($existingReponse[0]->getReponse() == $goodAnswer){
                    $reponses[] = [$existingReponse[0]->getReponse(), 'vrai'];
                }
                else{
                    $reponses[] = [$existingReponse[0]->getReponse(), 'faux'];
                }
            }
            else{
                $reponses[] = ['Vous n\'avez pas encore répondu à cette question', ''];
            }
            $i++;
        }
        $form_questions = $form->getForm();

        $form_questions->handleRequest($request);
        if ($form_questions->isSubmitted() && $form_questions->isValid()){
            $answers = $form_questions->getData();

            for($k = 0; $k < count($answers); $k++) {
                if ($answers[$k+1]){
                    // checkez si une réponse existe déja
                    $existingReponse = $reponseRepository->findBy(['user' => $user->getId(), 'question' => $questions[$k]->getId()]);
                    if ($existingReponse) {
                        //update le resultat de la réponse
                        $existingReponse[0]->setReponse($answers[$k + 1]);

                        $manager->persist($existingReponse[0]);
                    } //sinon créer la réponse
                    else {
                        $reponse = new Reponse();
                        $reponse->setQuestion($questions[$k]);
                        $reponse->setUser($user);
                        $reponse->setReponse($answers[$k + 1]);

                        $manager->persist($reponse);
                    }

                    //resultats
                    $result = $resultRepository->findOneBy(['user' => $user, 'quiz' => $quiz->getId()]);
                    if ($answers[$k + 1] == $questions[$k]->getAnswer()[0]) {
                        if ($result) {
                            $result->setResultat($result->getResultat()+1);
                        } else {
                            $result = new Result();
                            $result->setQuiz($quiz);
                            $result->setUser($user);
                            $result->setResultat(1);
                            $manager->persist($result);
                            $manager->flush();
                        }
                    }
                }
            }

            if (!isset($result)){
                $result = new Result();
                $result->setQuiz($quiz);
                $result->setUser($user);
                $result->setResultat(0);
            }

            $manager->persist($result);

            $manager->flush();

            return $this->redirectToRoute('quiz_list');
        }

        return $this->render('quiz/show.html.twig', [
            'quiz' => $quiz,
            'questions' => $questions,
            'form' => $form_questions->createView(),
            'answer' => 'answer',
            'reponses' => $reponses
        ]);
    }

    /**
     * @Route("/admin/quiz_add", name="quiz_add")
     * @Route("/admin/quiz/{id}/edit", name="quiz_edit")
     */
    public function form(Quiz $quiz = null, Request $request, EntityManagerInterface $manager){
        if(!$quiz){
            $quiz = new Quiz();
        }

        $formQuiz = $this->createForm(QuizType::class, $quiz);
        $formQuiz->handleRequest($request);

        if ($formQuiz->isSubmitted() && $formQuiz->isValid()){
            $manager->persist($quiz);
            $manager->flush();

            return $this->redirectToRoute('quiz_list');
        }

        return $this->render('quiz/form.html.twig', [
            'formQuiz' => $formQuiz->createView(),
            'editMode' => $quiz->getId() !== null
        ]);
    }

    /**
     * @Route("/quiz_reset/{quizID}", name="quiz_reset")
     */
    public function reset($quizID, QuizRepository $repository, ReponseRepository $reponseRepository,
                          ResultRepository $resultRepository, EntityManagerInterface $manager){
        $user = $this->getUser();
        $quiz = $repository->find($quizID);
        $result = $resultRepository->findOneBy(['user' => $user, 'quiz' => $quiz]);

        if($result){
            $questions = $quiz->getQuestions();
            foreach ($questions as $question){
                $reponse = $reponseRepository->findOneBy(['user' => $user, 'question' => $question]);
                $manager->remove($reponse);
            }
            $manager->remove($result);
            $manager->flush();
        }
        return $this->redirectToRoute('quiz_list');
    }

    /**
     * @Route("/admin/quiz/{quizID}/questions", name="quiz_questions")
     */
    public function questions($quizID, QuizRepository $quizRepository){

        $quiz = $quizRepository->find($quizID);
        $questions = $quiz->getQuestions();

        return $this->render('quiz/questions.html.twig', [
            'quiz' => $quiz,
            'questions' => $questions
        ]);
    }

    /**
     * @Route("/admin/quiz/{id}/delete", name="quiz_delete")
     */
    public function delete(Quiz $quiz, ReponseRepository $reponseRepository,
                           ResultRepository $resultRepository, EntityManagerInterface $manager){

        $questions = $quiz->getQuestions();
        foreach ($questions as $question){
            $reponses = $reponseRepository->findBy(['question' => $question]);
            foreach ($reponses as $reponse){
                $manager->remove($reponse);
            }
            $manager->remove($question);
        }

        $results = $resultRepository->findBy(['quiz' => $quiz]);
        foreach ($results as $result){
            $manager->remove($result);
        }
        $manager->remove($quiz);
        $manager->flush();

        return $this->redirectToRoute('quiz_list');
    }
}
