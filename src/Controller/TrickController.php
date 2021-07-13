<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Service\Slug;
use App\Service\UploadImage;
use Doctrine\ORM\EntityManagerInterface;
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
    public function add(Request $request, UploadImage $uploadImage, EntityManagerInterface $manager, Slug $slug): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setUser($this->getUser());
            $trick->setCreatedAt(new \DateTime());
            $sluggy = $slug->slugify($trick->getTitle());
            $trick->setSlug($sluggy);

            foreach ($trick->getImages() as $image) {
                $image = $uploadImage->saveImage($image);
                $image->setUser($this->getUser());
                $manager->persist($image);
            }
            foreach ($trick->getVideos() as $video) {
                dump($video);
                $video->setUser($this->getUser());
                $manager->persist($video);
            }
            $manager->persist($trick);
            $manager->flush();
            return $this->redirectToRoute('app_home');
        }
        return $this->render('trick/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
