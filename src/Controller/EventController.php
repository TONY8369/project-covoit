<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Entity\EventRequest;
use App\Entity\User;
use App\Service\EmailBuilder;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/event')]
class EventController extends AbstractController
{

    #[Route('/', name: 'event_index', methods:['GET'])]
    /**
     * this function list event
     * @param EventRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(EventRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $events = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        //dd($repository->findAll());
        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);

        
    }

    #[Route('/new', 'event_new', methods: ['GET', 'POST'])]
    /**
     * This function create event
     * @param Request $request
     * @param EventRepository $eventRepository
     * @return Response
     */
    public function new(Request $request, EventRepository $eventRepository): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        //     $event = $form->getData(); // récupère la donnée
        // //     $manager->persist($event); // transfère dans la base de donnée
        // //     $manager->flush();
            $eventRepository->save($event, true);

            $this->addFlash('success', 'Votre évènement à bien était crée !');
            // $emailBuilder->sendEmail($user);

            return $this->redirectToRoute('event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
            // ->createView()
        ]);
    }
    
    #[Route('/{id}', name: 'event_show', methods: ['GET'])]
    /**
     * This function show event
     *
     * @param Event $event
     * @return Response
     */
    public function show(Event $event, EntityManagerInterface $manager): Response
    {
        // Récupération de l'utilisateur
        $users = $manager->getRepository(User::class)->findAll();
        // dd($users);
        // Récupération de l'utilisateur
        $eventRequests = $manager->getRepository(EventRequest::class)->findAll();
        // dd($eventRequests);
        return $this->render('event/show.html.twig', [
            'event' => $event,
            'users' => $users,
            'eventRequests' => $eventRequests,
        ]);
    }

    #[Route('edit/{id}', 'event_edit', methods: ['GET', 'POST'])]
    /**
     * this function edit event
     * @param Event $event
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(Event $event, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //dd($form->getData());
            $event === $form->getData(); // récupère la donnée
            $manager->persist($event); // transfère dans la base de donnée
            $manager->flush();

            $this->addFlash('success', 'Votre évènement à bien était modifié !');

            return $this->redirectToRoute('event_index');
        }
        return $this->render('event/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('proposition/{id}', 'propal_show', methods: ['GET', 'POST'])]
    /**
     * this function edit proposition event
     * @param Event $event
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function PropalShow(Event $event ,EntityManagerInterface $manager): Response
    {
        // id de l'event
        $eventId = $event->getId();
        //  dd($eventId);

        // Récupération de l'utilisateur
        $users = $manager->getRepository(User::class)->findAll();
        // dd($user);
        return $this->render('event/propal.html.twig', [
            'event' => $event,
            'eventId'=> $eventId,
            'users'=> $users
        ]);
    }

    #[Route('PropositionEdition_post/', 'propal_edit_post', methods: ['GET', 'POST'])]
    /**
     * this function edit à propal event
     * @param Event $Event
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function PropalNews(Request $request, EntityManagerInterface $manager): Response
    {
        // On crée un event request
        $eventRequest = new EventRequest();
        // Ont récupère eventId
         $eventId = $request->get('event_id') ;
         $event = $manager->getRepository(Event::class)->find($eventId);
        //dd($event);
         $eventRequest->setEvent($event);
        //dd($event , $eventId);
         //dd($eventId);
        // on récupère le userId
        $userId = $request->get('user_id');
        //  récupère les données user
        $user = $manager->getRepository(User::class)->find($userId);
        // dd($user);
        $eventRequest->setUser($user);
        //  récupère les données type
        $eventRequest->setType($request->get('type'));
        //  récupère les données nb_seat
        $eventRequest->setNbSeat($request->get('nb_seat'));
        //  récupère les données adress
        $eventRequest->setAddress($request->get('adress'));

        //récupère les données departure_time
        $datetime = $request->get('departure_time');
        $date = new \DateTimeImmutable($datetime);
        $hour = date('H:i', strtotime($datetime));

        $eventRequest->setDepartureTime($date); 
        //  récupère les données direction
        $eventRequest->setDirection($request->get('direction'));
        
        $manager->persist($eventRequest);// transfère dans la base de donnée
        $manager->flush();

        $this->addFlash('success', 'La proposition à bien était envoyer !');

        return $this->redirectToRoute('event_show',[
            'id' => $eventId,
        ]);
    }

    #[Route('/delete/{id}', 'event_delete', methods: ['GET'])]
    /**
     * This function delete event
     * @param EntityManagerInterface $manager
     * @param Event $event
     * @return Response
     */
    public function delete(EntityManagerInterface $manager, Event $event): Response
    {
        $manager->remove($event);
        $manager->flush();

        $this->addFlash('success', 'Votre évènement à bien était supprimé !');

        return $this->redirectToRoute('event_index');
    }
}
