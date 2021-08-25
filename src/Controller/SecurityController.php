<?php

namespace App\Controller;

use App\Form\EditProfilType;
use App\Security\EmailVerifier;
use App\Service\UploadImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

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
     * @Route("/logout_custom", name="app_logout_custom")
     */
    public function logoutCustom()
    {
        if($this->get('security.token_storage')->getToken()->getUser()){
            $this->get('security.token_storage')->setToken(null);
            return $this->redirectToRoute('app_home');
        }
        return $this->redirectToRoute('app_home');
    }


    /**
     * @Route("/profil", name="app_profil")
     */
    public function profil()
    {
        $user = $this->getUser();
        return $this->render('security/profil.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/edit-profil", name="app_edit_profil")
     */
    public function edit(Request $request, EntityManagerInterface $em, UploadImage $uploadImage)
    {
        $user = $this->getUser();
        $email_user = $user->getEmail();
        $baseAvatar = ['Multiavatar-1.png', 'Multiavatar-2.png', 'Multiavatar-3.png', 'Multiavatar-4.png'];

        $form = $this->createForm(EditProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldAvatar = $user->getAvatar();
            $avatarFile = $form['avatar']->getData();
            $email = $form['email']->getData();
            if ($avatarFile) {
                $user->setAvatar('');
                $fichier = $uploadImage->saveAvatar($avatarFile);
                if ($oldAvatar && !in_array($oldAvatar, $baseAvatar)) {
                    unlink($this->getParameter('avatar_directory') . '/' . $oldAvatar);
                }
                $user->setAvatar($fichier);
            }
            if ($email != $email_user) {
                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                    (new TemplatedEmail())
                        ->from(new Address('no-reply@snowtricks.com', 'Snowtricks.com'))
                        ->to($email)
                        ->subject('Confirm your email !')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );
                $user->setIsVerified(false);
                $em->flush();
                $this->addFlash('success', 'Please check your email for confirmation');
                return $this->redirectToRoute('app_logout_custom');
            }
            $em->flush();

            $this->addFlash('success', 'Your profil has been modified');
            // do anything else you need here, like send an email
            return $this->redirectToRoute('app_profil');
        }

        return $this->render('security/edit_profil.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
