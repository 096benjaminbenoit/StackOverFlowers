<?php

namespace App\Controller;

use DateTime;
use App\Entity\Thread;
use App\Entity\Comment;
use App\Form\ThreadType;
use App\Form\CommentType;
use App\Repository\UserRepository;
use App\Repository\ThreadRepository;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/thread')]
class ThreadController extends AbstractController
{
    // #[Route('/', name: 'app_thread_index', methods: ['GET'])]
    // public function index(ThreadRepository $threadRepository): Response
    // {
    //     return $this->render('thread/index.html.twig', [
    //         'threads' => $threadRepository->findBy([], ['post_date' => 'DESC']),
    //     ]);
    // }

    #[Route('/new', name: 'app_thread_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ThreadRepository $threadRepository): Response
    {
        $thread = new Thread();
        $thread->setPostDate(new \DateTime("now"));
        $form = $this->createForm(ThreadType::class, $thread);
        $form->handleRequest($request);
        $thisUser = $this->getUser();

        $thread->setPostDate(new DateTime("now"));
        $thread->setUser($thisUser);
        $thread->setStatus('Active');

        if ($form->isSubmitted() && $form->isValid()) {
            $threadRepository->save($thread, true);

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('thread/new.html.twig', [
            'thread' => $thread,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_thread_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Thread $thread, CommentRepository $commentRepository, int $id, UserRepository $userRepository): Response
    {
        $comments = $commentRepository->findBy([], ['comment_date' => 'DESC']);
        $users = $userRepository->findAll();
        $thisUser = $this->getUser();

        $comment = new Comment();
        $comment->setThread($thread);
        $comment->setUser($thisUser);
        $comment->setCommentDate(new DateTime("now"));
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentRepository->save($comment, true);

            return $this->redirectToRoute('app_thread_show', ['id' => $thread->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('thread/index.html.twig', [
            'thread' => $thread,
            'comments' => $comments,
            'users' => $users,
            'form' => $form
        ]);
    }

    // #[Route('/{id}/edit', name: 'app_thread_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Thread $thread, ThreadRepository $threadRepository): Response
    // {
    //     $form = $this->createForm(ThreadType::class, $thread);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $threadRepository->save($thread, true);

    //         return $this->redirectToRoute('app_thread_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('thread/edit.html.twig', [
    //         'thread' => $thread,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_thread_delete', methods: ['POST'])]
    // public function delete(Request $request, Thread $thread, ThreadRepository $threadRepository): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$thread->getId(), $request->request->get('_token'))) {
    //         $threadRepository->remove($thread, true);
    //     }

    //     return $this->redirectToRoute('app_thread_index', [], Response::HTTP_SEE_OTHER);
    // }
}
