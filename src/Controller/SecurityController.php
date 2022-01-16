<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Usercontent;
use App\Form\UserType;
use App\Form\UsercontentType;
use App\Repository\UserRepository;
use App\Repository\ContentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     * Page de création d'un nouvel utilisateur en utilisant un Form
     */
        public function register(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, ContentRepository $contentRepository): Response {
            $user = new User();
            $content = $contentRepository->findAll();
            
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);
                
            if ($form->isSubmitted() && $form->isValid()){
                
                /* Hasher permettant de coder le mot de passe dans la DB avant de l'y envoyer*/
                
                $user->setPassword($passwordHasher->hashPassword($user, $user->getPlainPassword()));
                $user->setRoles(['ROLE_USER']);
                $em->persist($user);
                
                /* Lors de la création d'un utilisateur, une autre base est créée, qui va permettre à celui-ci de stocker la sauvegarde de ses données personelles sur le site*/
                    foreach($content as $content){
                        $usercontent= new Usercontent();               
                        $usercontent->setUserId($user);
                        $usercontent->setContentId($content);
                /* ->setState(0) : Permet de garder toutes les données des contenus considérées comme False, afin de pouvoir les modifier après à sa guise */
                        $usercontent->setState(0);
                        $em->persist($usercontent);
                }
                
                $em->flush();
                return $this->redirectToRoute('login');
            }
            return $this->render('pages/register.html.twig', ['UserForm' => $form->createView()]);
        }

    /**
     * @Route("/edituser/{id<\d+>}", name="edituser")
     * Page permettant à l'utilisateur d'accéder à ses informations de profil et de pouvoir les modifier si besoin
     */
        public function edituser(int $id, Request $request, UserRepository $userRepository, EntityManagerInterface $em): Response
            {
                $user = $userRepository->find($id);
                $form = $this->createForm(UserType::class, $user);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $em->persist($user);
                    $em->flush();
                    return $this->redirectToRoute('home');
            }
            return $this->render('pages/register.html.twig', ['UserForm' => $form->createView(), 'user' => $user]);
        }

    /**
     * @Route("/login", name="login")
     * Fonction de connexion au site
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="logout")
     * Fonction de déconnexion du site
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
