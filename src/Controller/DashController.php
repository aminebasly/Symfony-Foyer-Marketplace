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

    #[Route('/layouts', name: 'menu')]
    public function menu(){
        return $this->render('dash/layouts-without-menu.html.twig');
    }
        
    #[Route('/new', name: 'la')]
    public function new(){
        return $this->render('dash/pro.html.twig', [
            'controller_name' => 'DashController',
        ]);
    }

    #[Route('/forgot', name: 'forgot')]
    public function auth_forgot_password_basic(){
        return $this->render('dash/auth-forgot-password-basic.html.twig', [
            'controller_name' => 'DashController',
        ]);
    }

    // #[Route('/loginnn', name: 'loginnn')]
    // public function login(){
    //     return $this->render('dash/auth-login-basic.html.twig', [
    //         'controller_name' => 'DashController',
    //     ]);
    // }

    #[Route('/register', name: 'register')]
    public function register(){
        return $this->render('dash/auth-register-basic.html.twig', [
            'controller_name' => 'DashController',
        ]);
    }

    #[Route('/navbar', name: 'navbar')]
    public function navbar(){
        return $this->render('dash/layouts-without-navbar.html.twig', [
            'controller_name' => 'DashController',
        ]);
    }

    #[Route('/container', name: 'container')]
    public function container(){
        return $this->render('dash/layouts-container.html.twig', [
            'controller_name' => 'DashController',
        ]);
    }

    #[Route('/fluid', name: 'fluid')]
    public function fluid(){
        return $this->render('dash/layouts-fluid.html.twig', [
            'controller_name' => 'DashController',
        ]);
    }

    #[Route('/blank', name: 'blank')]
    public function blank(){
        return $this->render('dash/layouts-blank.html.twig', [
            'controller_name' => 'DashController',
        ]);
    }

    #[Route('/users', name: 'users')]
    public function user(){
        return $this->render('dash/users.html.twig', [
            'controller_name' => 'DashController',
        ]);
    }

}
