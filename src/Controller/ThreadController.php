<?php

namespace App\Controller;

use App\Entity\Thread;
use App\Entity\Comment;
use App\Form\ThreadType;
use App\Repository\CommentRepository;
use App\Repository\ThreadRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/thread')]
class ThreadController extends AbstractController
{
    #[Route('/', name: 'app_thread_index', methods: ['GET'])]
    public function index(ThreadRepository $threadRepository): Response
    {
        return $this->render('thread/index.html.twig', [
            'threads' => $threadRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_thread_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ThreadRepository $threadRepository): Response
    {
        $thread = new Thread();
        $form = $this->createForm(ThreadType::class, $thread);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $threadRepository->save($thread, true);

            return $this->redirectToRoute('app_thread_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('thread/new.html.twig', [
            'thread' => $thread,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_thread_show', methods: ['GET'])]
    public function show(Thread $thread, CommentRepository $commentRepository, int $id, UserRepository $userRepository): Response
    {
        $comments = $commentRepository->descSort();
        $users = $userRepository->findAll();

        return $this->render('thread/show.html.twig', [
            'thread' => $thread,
            'comments' => $comments,
            'users' => $users
        ]);
    }

    #[Route('/{id}/edit', name: 'app_thread_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Thread $thread, ThreadRepository $threadRepository): Response
    {
        $form = $this->createForm(ThreadType::class, $thread);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $threadRepository->save($thread, true);

            return $this->redirectToRoute('app_thread_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('thread/edit.html.twig', [
            'thread' => $thread,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_thread_delete', methods: ['POST'])]
    public function delete(Request $request, Thread $thread, ThreadRepository $threadRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$thread->getId(), $request->request->get('_token'))) {
            $threadRepository->remove($thread, true);
        }

        return $this->redirectToRoute('app_thread_index', [], Response::HTTP_SEE_OTHER);
    }
}
