<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashController extends AbstractController
{
    #[Route('/dash', name: 'app_dash')]
    public function index(): Response
    {
        return $this->render('dash/index.html.twig', [
            'controller_name' => 'DashController',
        ]);
    }

    #[Route('/forgot', name: 'forgot')]
    public function auth_forgot_password_basic(){
        return $this->render('dash/auth-forgot-password-basic.html.twig', [
            'controller_name' => 'DashController',
        ]);
    }

    #[Route('/loginnn', name: 'login')]
    public function login(){
        return $this->render('dash/auth-login-basic.html.twig', [
            'controller_name' => 'DashController',
        ]);
    }

    #[Route('/register', name: 'register')]
    public function register(){
        return $this->render('dash/auth-register-basic.html.twig', [
            'controller_name' => 'DashController',
        ]);
    }
}
