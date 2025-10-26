<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     */
    public function index()
    {
        $userResults = [];

        if ($this->getUser()){
            $user = $this->getUser();
            $userResults = $user->getResults();
        }

        return $this->render('account/index.html.twig', [
            'results' => $userResults
        ]);
    }

    /**
     * @Route("/account/{id}/edit", name="account_edit")
     */
    public function edit(User $user, Request $request, EntityManagerInterface $manager){

        $connectedUser = $this->getUser();
        if ($connectedUser != $user && !$this->isGranted('ROLE_ADMIN')){
            $this->denyAccessUnlessGranted(null);
        }

        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('account');
        }

        return $this->render('account/form.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
