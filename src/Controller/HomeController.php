<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(TrickRepository $trickRepository): Response
    {
        $limit = 5;
        $pageNb = 1;
        $nbPages = ($trickRepository->getTotalTricks() / $limit);

        $tricks = $trickRepository->getPaginatedTrick($pageNb, $limit);

        $total = $trickRepository->getTotalTricks();
        return $this->render('home/index.html.twig', [
            'tricks' => $tricks,
            'total' => $total,
            'limit' => $limit,
            'pageNb' => $pageNb,
            'nbPages' => $nbPages
        ]);
    }

    /**
     * @Route("/ajax/{nbTricks}", name="app_home_ajax")
     */
    public function _loadMoreTrick(TrickRepository $trickRepository, Request $request, $nbTricks): Response
    {
        $limit = 5;
        $tricks = $trickRepository->findBy([], ['created_at' => 'desc'], $limit, $nbTricks);

        if(!$request->isXmlHttpRequest()){
            return $this->redirectToRoute("app_home");
        }
        if(!$tricks){
            return new JsonResponse([
                'content' => false
            ]);
        }
            return new JsonResponse([
                'content' => $this->renderView('home/_tricks.html.twig', [ "tricks" => $tricks])
            ]);

    }
}
