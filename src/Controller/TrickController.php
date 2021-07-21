<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
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
            $sluggy = $slug->slugify($trick->getTitle());
            $trick->setSlug($sluggy);


            foreach ($trick->getImages() as $image) {
                dump($image);
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

    /**
     * @Route("/trick/details/{slug}", name="trick_details")
     */
    public function details(Request $request, TrickRepository $trickRepository, CommentRepository $commentRepository, EntityManagerInterface $manager, $slug): Response
    {
        $trick = $trickRepository->findOneBy(['slug' => $slug]);

        $limit = 5;
        $pageNb = 1;

        $comments = $commentRepository->getPaginatedComment($pageNb, $limit, $trick->getId());


        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment->setUser($this->getUser());
            $comment->setTrick($trick);

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('success', 'Votre commentaire à bien été ajouté');

            return $this->redirectToRoute('trick_details', ['slug' => $trick->getSlug()]);
        }

        return $this->render('trick/trick_details.html.twig', [
            'trick' => $trick,
            'comments' => $comments,
            'form' => $form->createView(),
        ]);
    }
}
