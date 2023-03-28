<?php

namespace App\Controller;

use App\Repository\TechnologyRepository;
use App\Repository\ThreadRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ThreadRepository $threadRepository, PaginatorInterface $paginator, Request $request, TechnologyRepository $technologyRepository): Response
    {
        $threads = $threadRepository->descSort();

        $pagination = $paginator->paginate(
            $threads,
            $request->query->getInt('page', 1),
            10
        );

        $technolgys = $technologyRepository->findAll();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'threads' => $threads,
            'pagination' => $pagination,
            'technologys' => $technolgys
        ]);
    }
}
