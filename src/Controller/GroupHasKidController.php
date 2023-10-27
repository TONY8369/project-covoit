<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\GroupHasKid;
use App\Entity\Kid;
use App\Form\GroupHasKidType;
use App\Repository\GroupRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/groupHasKid')]
class GroupHasKidController extends AbstractController
{
    #[Route('/{id}', name: 'groupHasKid_show')]
    public function show(
        EntityManagerInterface $entityManager, Group $group): Response
    {
        //RECUPERER GROUP ID 
        // AFFICHER LES DETAILS DU GROUPE 
        // + LES ENFANTS RATTACHES AU GROUPE EN QUESTION 

        $repository = $entityManager->getRepository(Kid::class);
        $kids = $repository->findAll();

        foreach ($kids as $kid) {
            $kid->kids = $repository->searchIdToGroup($kid->getId());
        }
        //dd($kids);

        return $this->render('group/edit.html.twig', [
            'kids' => $kids,
        ]);
    }

    #[Route('/new', name: 'groupHasKid_new')]
    public function new(EntityManagerInterface $entityManager, GroupRepository $groupRepository, Request $request):Response
    {
        $kid = new Kid();
        // dd($request->query->get('group_id'));

        $form = $this->createForm(GroupHasKidType::class, $kid);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $kid = $form->getData();

            $entityManager->persist($kid);
            $entityManager->flush();
            //Create grouphaskid -> group_id vient de l'url au parametre group_id -> kid_id vient du kid que l'on vient de créer 
            // dd($kid);
            $groupHasKid = new GroupHasKid();
            $groupHasKid->setKid($kid);
            $group_id = $request->query->get('group_id');
            // $entityManager->getRepository(Group::class); // = groupRepository pourquoi group n'était pas utilisable ? 
            $group = $groupRepository->find($group_id);
            $groupHasKid->setGroup($group);
            // dd($groupHasKid);
            // dd($groupHasKid, $kid, $group); // -> {"kid_id" => $kid->getId(), "group_id" =>$request->query->get('group_id') }
            $entityManager->persist($groupHasKid);
            $entityManager->flush();

            $this->addFlash('success', 'L\'adhérent a bien été ajouté au groupe !');
        }

        return $this->render('groupHasKid/index.html.twig', [
            'form' => $form->createView()
        ]);
    }


}
