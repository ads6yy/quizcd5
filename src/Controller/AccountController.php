<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
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
        return $this->render('account/index.html.twig', [
        ]);
    }

    /**
     * @Route("/account/{id}/edit", name="account_edit")
     */
    public function edit(User $user, Request $request, ObjectManager $manager){


        return $this->render('account/form.html.twig', [
        ]);
    }
}
