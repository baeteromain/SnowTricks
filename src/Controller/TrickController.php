<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\User;
use App\Form\TrickType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    /**
     * @Route("/trick", name="trick")
     */
    public function index(): Response
    {
        return $this->render('trick/index.html.twig', [
            'controller_name' => 'TrickController',
        ]);
    }

    /**
     * @Route("/trick/add", name="trick_add")
     */
    public function add(Request $request): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
//                $trick->setUser('');
                $trick->setCreatedAt( new \DateTime());
                $trick->setSlug('test');

                $images = $form->get('images')->getData();

                foreach ($images as $image){
                    $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                    $image->move(
                        $this->getParameter('trick_image_directory'),
                        $fichier
                    );
                    $img = new Image();
                    $img->setName($fichier);
                    $trick->addImage($img);
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($trick);
                $em->flush();
              return $this->redirectToRoute('app_home');
            }
        return $this->render('trick/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
