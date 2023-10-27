<?php

namespace App\Controller;

use App\Entity\Association;
use App\Form\AssociationType;
use App\Entity\UserHasAssociation;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AssociationRepository;
use \Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserHasAssociationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/association')]
class AssociationController extends AbstractController
{
    #[Route('/home', name: 'home', methods: ['GET'])]
    public function home(Security $security): Response
    {

        // dd($security->getUser());
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/', name: 'association_index', methods: ['GET'])]
    public function index(
        AssociationRepository $associationRepository
        ): Response
    {
        return $this->render('association/index.html.twig', [
            'associations' => $associationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'association_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        AssociationRepository $associationRepository
        ): Response
    {
        $association = new Association();
        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);
        // dd($association);


        // $assoId = $association->getId();
        // dd($assoId);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $security->getUser();
            //dd($user);
            if ($user) {
                // $userRepository->save($user, true);

                return $this->redirectToRoute('association_index', [], Response::HTTP_SEE_OTHER);
            }

            //TODO : SET USER ROLE TO ADMIN à partir de l'utilisateur qui créé l'event dans user_has_association

            return $this->render('association/new.html.twig', [
                'association' => $association,
                'form' => $form,
            ]);
        }
    }

    #[Route('/{id}', name: 'association_show', methods: ['GET'])]
    public function show(Association $association): Response
    {
        return $this->render('association/show.html.twig', [
            'association' => $association,
        ]);
    }

    #[Route('/{id}/edit', name: 'association_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        Association $association, 
        AssociationRepository $associationRepository
        ): Response
    {
        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $associationRepository->save($association, true);

            return $this->redirectToRoute('association_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('association/edit.html.twig', [
            'association' => $association,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'association_delete', methods: ['POST'])]
    public function delete(
        Request $request, 
        Association $association, 
        AssociationRepository $associationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $association->getId(), $request->request->get('_token'))) {
            $associationRepository->remove($association, true);
        }

        return $this->redirectToRoute('association_index', [], Response::HTTP_SEE_OTHER);
    }
}
