<?php

namespace App\Controller;

use App\Entity\EventRequest;
use App\Form\EventRequestType;
use App\Repository\EventRequestRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/event_request')]
class EventRequestController extends AbstractController
{
    #[Route('/', name: 'event_request_index', methods: ['GET'])]
    public function index(EventRequestRepository $eventRequestRepository): Response
    {
        return $this->render('event_request/index.html.twig', [
            'eventRequests' => $eventRequestRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'event_request_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EventRequestRepository $eventRequestRepository): Response
    {
        $eventRequest = new EventRequest();
        $form = $this->createForm(EventRequestType::class, $eventRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRequestRepository->save($eventRequest, true);

            return $this->redirectToRoute('event_request_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event_request/new.html.twig', [
            'event_request' => $eventRequest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'event_request_show', methods: ['GET'])]
    public function show(EventRequest $eventRequest): Response
    {
        return $this->render('event_request/show.html.twig', [
            'event_request' => $eventRequest,
        ]);
    }

    #[Route('/{id}/edit', name: 'event_request_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EventRequest $eventRequest, EventRequestRepository $eventRequestRepository): Response
    {
        $form = $this->createForm(EventRequestType::class, $eventRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRequestRepository->save($eventRequest, true);

            return $this->redirectToRoute('event_request_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event_request/edit.html.twig', [
            'event_request' => $eventRequest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'event_request_delete', methods: ['POST'])]
    public function delete(Request $request, EventRequest $eventRequest, EventRequestRepository $eventRequestRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$eventRequest->getId(), $request->request->get('_token'))) {
            $eventRequestRepository->remove($eventRequest, true);
        }

        return $this->redirectToRoute('event_request_index', [], Response::HTTP_SEE_OTHER);
    }
}
