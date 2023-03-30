<?php

namespace App\Controller\Admin;

use App\Entity\Thread;
use App\Form\ThreadType;
use App\Form\Thread1Type;
use App\Form\AdminThreadType;
use App\Repository\ThreadRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/thread')]
class ThreadController extends AbstractController
{
    #[Route('/', name: 'app_admin_thread_index', methods: ['GET'])]
    public function index(ThreadRepository $threadRepository): Response
    {
        return $this->render('admin/thread/index.html.twig', [
            'threads' => $threadRepository->findBy([], ['post_date' => 'DESC']),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_thread_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Thread $thread, ThreadRepository $threadRepository): Response
    {
        $form = $this->createForm(AdminThreadType::class, $thread);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $threadRepository->save($thread, true);

            return $this->redirectToRoute('app_admin_thread_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/thread/edit.html.twig', [
            'thread' => $thread,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_thread_delete', methods: ['POST'])]
    public function delete(Request $request, Thread $thread, ThreadRepository $threadRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$thread->getId(), $request->request->get('_token'))) {
            $threadRepository->remove($thread, true);
        }

        return $this->redirectToRoute('app_admin_thread_index', [], Response::HTTP_SEE_OTHER);
    }
}
