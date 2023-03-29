<?php

namespace App\Controller;

use App\Form\ThreadSearchType;
use App\Repository\TechnologyRepository;
use App\Repository\ThreadRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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
        $searchThreadForm = $this->createForm(ThreadSearchType::class);

        $threads = $threadRepository->findBy([], ['post_date' => 'DESC']);
        
        if($searchThreadForm->handleRequest($request)->isSubmitted() && $searchThreadForm->isValid()) {
            $criteria = $searchThreadForm->getData();
        }

        $pagination = $paginator->paginate(
            $threadRepository->searchThread($criteria ?? []),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'threads' => $threads,
            'pagination' => $pagination,
            'search_form' => $searchThreadForm
        ]);
    }
}
