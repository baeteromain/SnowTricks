<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use App\Service\Slug;
use App\Service\UploadImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            $images = $form->get('images')->getData();


            foreach ($images as $image) {
                $fichier = $uploadImage->saveImage($image);
                $img = new Image();
                $img->setName($fichier);
                $img->setUser($this->getUser());
                $trick->addImage($img);
            }
            foreach ($trick->getVideos() as $video) {
                dump($video);
                $video->setUser($this->getUser());
                $manager->persist($video);
            }
            $manager->persist($trick);
            $manager->flush();
            $this->addFlash('success', 'your trick has been added');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('trick/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/trick/{slug}", name="trick_details")
     */
    public function details(Request $request, TrickRepository $trickRepository, CommentRepository $commentRepository, EntityManagerInterface $manager, $slug): Response
    {
        $trick = $trickRepository->findOneBy(['slug' => $slug]);


        $limit = 2;
        $pageNb = 1;
        $nbPages = ceil($commentRepository->getTotalComments($trick->getId()) / $limit);

        $comments = $commentRepository->getPaginatedComment($pageNb, $limit, $trick->getId());


        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setTrick($trick);

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('success', 'your comment has been added');

            return $this->redirectToRoute('trick_details', ['slug' => $trick->getSlug()]);
        }

        return $this->render('trick/trick_details.html.twig', [
            'trick' => $trick,
            'comments' => $comments,
            'form' => $form->createView(),
            'pages' => $nbPages,
        ]);
    }

    /**
     * @Route("/trick/{slug}/comments/{pageNb}", name="trick_ajax_comments")
     */
    public function _paginationComments(Request $request, TrickRepository $trickRepository, CommentRepository $commentRepository, EntityManagerInterface $manager, $slug, $pageNb)
    {
        $limit = 2;
        $trick = $trickRepository->findOneBy(['slug' => $slug]);
        $comments = $commentRepository->getPaginatedComment($pageNb, $limit, $trick->getId());
        if (!$request->isXmlHttpRequest()) {
            return $this->redirectToRoute('trick_details', ['slug' => $trick->getSlug()]);
        }

        return new JsonResponse([
            'content' => $this->renderView('trick/_comment.html.twig', ["comments" => $comments]),
        ]);

    }


    /**
     * @Route("/trick/edit/{id}", name="trick_edit")
     */
    public function edit(Request $request, TrickRepository $trickRepository, EntityManagerInterface $manager, $id, UploadImage $uploadImage, Slug $slug): Response
    {
        $trick = $trickRepository->findOneBy(['id' => $id]);


        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $trick->setUpdatedAt(new \DateTime());

            $sluggy = $slug->slugify($trick->getTitle());
            $trick->setSlug($sluggy);
            $images = $form->get('images')->getData();

            foreach ($images as $image) {
                $fichier = $uploadImage->saveImage($image);
                $img = new Image();
                $img->setName($fichier);
                $img->setUser($this->getUser());
                $trick->addImage($img);
            }
            foreach ($trick->getVideos() as $video) {
                $video->setUser($this->getUser());
                $manager->persist($video);
            }


            $manager->persist($trick);
            $manager->flush();


            $this->addFlash('success', 'Trick has been modified');

            return $this->redirectToRoute('trick_details', ['slug' => $trick->getSlug()]);
        }

        return $this->render('trick/edit_details.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/image/{id}", name="trick_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Image $image, Request $request, EntityManagerInterface $manager)
    {
        $data = json_decode($request->getContent(), true);

        if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {

            $nom = $image->getName();

            unlink($this->getParameter('trick_image_directory') . '/' . $nom);


            $manager->remove($image);
            $manager->flush();

            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }

    /**
     * @Route("/trick/delete/{id}", name="trick_delete", methods={"DELETE", "POST"})
     */
    public function delete(Request $request, Trick $trick, EntityManagerInterface $manager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $trick->getId(), $request->request->get('_token'))) {
            $filesystem = new Filesystem();
            foreach ($trick->getImages() as $image) {
                $filesystem->remove($this->getParameter('trick_image_directory') . '/' . $image->getName());
            }
            $manager->remove($trick);
            $manager->flush();
        }
        $this->addFlash('success', 'Trick has been deleted');

        return $this->redirectToRoute('app_home');
    }

}
