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
    public function index(TrickRepository $trickRepository, Request $request): Response
    {
        $limit = 4;
        $pageNb = 1;
        $nbPages = ($trickRepository->getTotalTricks() / $limit);

//        $tricks = $trickRepository->findBy([], ['created_at' => 'desc'], 15, 0);

        $tricks = $trickRepository->getPaginatedTrick($pageNb, $limit);

        $total = $trickRepository->getTotalTricks();

        if ($request->isXmlHttpRequest()){
            $pageNb = (int)$request->query->get('page');
            $offset =  ($pageNb - 1) * $limit;
            $tricks = $trickRepository->findBy([], ['created_at' => 'desc'],$limit, $offset);
            $nbPages = ($trickRepository->getTotalTricks() / $limit) - $pageNb;
            $pageNb++;
            return new JsonResponse([
                'content' => $this->renderView('home/_tricks.html.twig', [ "tricks" => $tricks,
                    "pageNb" => $pageNb,
                    "nbPages" => $nbPages])
            ]);

        }

        return $this->render('home/index.html.twig', [
            'tricks' => $tricks,
            'total' => $total,
            'limit' => $limit,
            'pageNb' => $pageNb,
            'nbPages' => $nbPages
        ]);
    }
}
