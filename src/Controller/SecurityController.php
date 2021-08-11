<?php

namespace App\Controller;

use App\Form\EditProfilType;
use App\Service\UploadImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/profil", name="app_profil")
     */
    public function profil()
    {
       $user = $this->getUser();
        return $this->render('security/profil.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/edit-profil", name="app_edit_profil")
     */
    public function edit(Request $request, EntityManagerInterface $em, UploadImage $uploadImage)
    {
        $user = $this->getUser();
        $baseAvatar = ['Multiavatar-1.png', 'Multiavatar-2.png', 'Multiavatar-3.png', 'Multiavatar-4.png'];

        $form = $this->createForm(EditProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $oldAvatar = $user->getAvatar();
          $avatarFile = $form['avatar']->getData();
          if($avatarFile){
            $user->setAvatar('');
            $fichier = $uploadImage->saveAvatar($avatarFile);
            if($oldAvatar && !in_array($oldAvatar, $baseAvatar)){
                unlink($this->getParameter('avatar_directory') . '/' . $oldAvatar);
              }
            $user->setAvatar($fichier);
          }
            $em->flush();
            $this->addFlash('success','Your profil has been modified');
        }
        return $this->render('security/edit_profil.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}
