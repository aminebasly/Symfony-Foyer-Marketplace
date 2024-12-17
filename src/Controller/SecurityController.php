<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Login;  // Entité associée à la connexion
use App\Form\LoginHType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Password\PasswordHasherInterface;
use App\Repository\MachineRepository;


use Doctrine\ORM\EntityManagerInterface; 

class SecurityController extends AbstractController
{
    
#[Route('/loginn', name: 'app_login', methods: ['GET', 'POST'])]
public function verif(UserRepository $userRepository, Request $request,MachineRepository $mr): Response
{
    $login = new User();
    $form = $this->createForm(LoginHType::class, $login);  
    $form->handleRequest($request);

   
    if ($form->isSubmitted() && $form->isValid()) {
        
        $user = $userRepository->findOneByEmail($login->getEmail());


       
        if ($user && $user->getMotDePasse() === $login->getMotDePasse()) {
           
            if ($user->getRole() === 'Étudiant') {
                $prenom = $user->getPrenom();
                $nom = $user->getNom();
                return $this->redirectToRoute('app_useretudiant',['nom'=>$nom,'prenom'=>$prenom]);
            } else {
                return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
            }
        } else {
            
            $this->addFlash('error', 'Identifiants invalides.');
        }
    }

    return $this->render('login/index.html.twig', [
        'login' => $login,
        'form' => $form->createView(),  // Affichage du formulaire
    ]);
}

    // #[Route('/loginn', name: 'app_login')]
    // public function verif(UserRepository $userRepository, Request $request): Response
    // {
    //     $login = new User();
    //     $form = $this->createForm(LoginHType::class, $login);  // Créer le formulaire LoginHType
    //     $form->handleRequest($request);

    //     // Récupérer tous les utilisateurs de la base de données
    //     $logins = $userRepository->findAll();

    //     // Vérifier si le formulaire est soumis et valide
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         foreach ($logins as $user) {  // Remplacer "article" par "user"
    //             if ($user->getRole() === $login->getRole()) {
    //                 // Comparer les mots de passe en utilisant l'encodeur
    //                 if ($user->getMotDePasse() === $login->getMotDePasse()) {
    //                     // Vérifier le rôle de l'utilisateur (admin ou utilisateur normal)
    //                     if ($user->getRole() === 'Étudiant') {
    //                         return $this->redirectToRoute('app_useretudiant');
    //                     } else {
    //                         return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    //                     }
    //                 }
    //             }
    //         }

    //         // Si aucune correspondance n'est trouvée
    //         $this->addFlash('error', 'Identifiants invalides.');
    //     }

    //     return $this->render('login/index.html.twig', [
    //         'login' => $login,
    //         'form' => $form->createView(),  // Affichage du formulaire
    //     ]);}
    
    
    // #[Route('/dashboard', name: 'dashboard')]
    // public function dashboard(): Response
    // {
    //     // Rediriger l'utilisateur en fonction de son rôle
    //     // if ($this->isGranted('ROLE_ETUDIANT')) {
    //         return $this->redirectToRoute('app_useretudiant');
    //     // }

    //     // if ($this->isGranted('ROLE_TECHNICIEN')) {
    //     //     return $this->redirectToRoute('user_technicien');
    //     // }

    //     // return $this->render('dashboard/index.html.twig');
    // }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/forgot-password', name: 'forgot_password')]
    public function forgotPassword(): Response
    {
        return $this->render('login/forgot_password.html.twig');
    }

    #[Route('/register', name: 'register')]
    public function register(Request $request,EntityManagerInterface $entityManager): Response
{
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        // Enregistrer l'utilisateur en base de données
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
    }

    return $this->render('login/register.html.twig', [
        'f' => $form->createView(),  // Passer 'form' au template
    ]);
}

  

    #[Route('/register', name: 'register', methods: ['GET','POST'])]
public function handleRegister(Request $request): Response
{
    if ($request->isMethod('POST')) {
    $userType = $request->request->get('user_type');
    $email = $request->request->get('email');
    $password = $request->request->get('password');
    $confirmPassword = $request->request->get('confirm_password');

    if ($password !== $confirmPassword) {
        $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
        return $this->redirectToRoute('register');
    }

    if ($userType === 'student') {
        // Logique spécifique aux étudiants
        $studentNumber = $request->request->get('student_number');
        $studentDepartment = $request->request->get('student_department');
        // Sauvegarder les données pour un étudiant...
    } elseif ($userType === 'technician') {
        // Logique spécifique aux techniciens
        $techSpecialty = $request->request->get('tech_specialty');
        $experienceYears = $request->request->get('experience_years');
        // Sauvegarder les données pour un technicien...
    }

    // Sauvegarder l'utilisateur en général
    $this->addFlash('success', 'Inscription réussie !');
    return $this->redirectToRoute('app_home');
}  
return $this->render('login/register.html.twig');
}

#[Route('/reset-password', name: 'reset_password')]
public function resetPassword(Request $request,): Response
{
    $email = $request->request->get('email');

    if ($email) {
        // Simulez l'envoi d'un email ou ajoutez votre logique ici
        $this->addFlash('success', 'Password reset instructions have been sent to your email.');
        return $this->redirectToRoute('app_login');
    }

    return $this->render('security/reset_password.html.twig');
}

}



