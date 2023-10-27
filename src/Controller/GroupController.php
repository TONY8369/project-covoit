<?php

namespace App\Controller;

use App\Entity\Group;
use App\Form\GroupType;
use App\Entity\Association;
use App\Entity\GroupHasKid;
use App\Form\EditGroupType;
use App\Entity\GroupHasEvent;
use App\Form\RegisterGroupType;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/group')]
class GroupController extends AbstractController
{

    // LIST OF ALL GROUPS 
    #[Route('/', name: 'group_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Group::class);
        $groups = $repository->findAll();

        return $this->render('group/index.html.twig', [
            'groups' => $groups
        ]);
    }

    // CREATE NEW GROUP
    #[Route('/new', name: 'group_new')]
    public function switch(
        GroupRepository $groupRepository,
        EntityManagerInterface $entityManager, 
        Request $request
        ): Response
    {

        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        
            $group = $form->getData();
            // TODO : Function temporaire en attente session
            $asso = $entityManager->getRepository(Association::class)->find(1);
            // dd($asso);
            $group->setAssociation($asso);

            //* Envoye les données soumisent *//
            //$entityManager->persist($group);
            //* Execute Query *//
            //$entityManager->flush();

             $groupRepository->save($group, true);

            $this->addFlash('success', 'Le groupe à bien été créé !');

            return $this->redirectToRoute('group_index');
        }

        $groups = $groupRepository->findAll();

        return $this->render('group/new.html.twig', [
            //'controller_name' => 'RegisterGroup_Controller',
            'form' => $form->createView(),
            'groups' => $groups
        ]);
    }

    // EDIT EXISTING GROUP
    #[Route('/edit/{id}', name: 'group_edit', methods: ['GET', 'POST'])]
    public function edit(
        GroupRepository $groupRepository, 
        Group $group, 
        Request $request
        ): Response
    {

        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);
        //notre group contient des kids, on veut à présent afficher tous ces kids dans la vue. pour ce faire 
        // il faut aller cher cher tous ces kids dans la db avec une requete . craeteQuery // craeteQueryBuilder // autre... 
        // et l'insérer dans la vue . 
        $formDelete = $this->createFormBuilder()
            ->setAction($this->generateUrl('group_delete'))
            ->setMethod('POST')
            ->add('id', HiddenType::class, [
                'data' => $group->getId()
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Supprimer',
                'attr' => [
                    'class' => 'btn btn-danger'
                ],
            ]);

        if ($form->isSubmitted() && $form->isValid()) {
            // TODO : Function temporaire en attente session
            // $asso = $entityManager->getRepository(Association::class)->find(1);
            $groupRepository->save($group, true);
            return $this->redirectToRoute('group_index');
        }

        return $this->render('group/edit.html.twig', [
          //  'controller_name' => 'RegisterGroup_Controller',
            'form' => $form->createView(),
            'group_id' => $group->getId(),
            'formDelete' => $formDelete->getForm(),
        ]);
    }

    


    // DELETE A GROUP 
    #[Route('/delete', name: 'group_delete', methods: ['POST'])]
    public function delete(
        EntityManagerInterface $entityManager, 
        Request $request
        ): Response
    {

        $formData = $request->get('form');

        if ($formData) {

            $grouprepo = $entityManager->getRepository(Group::class);
            $group =  $grouprepo->find($formData['id']);
            $grouprepo->remove($group, true);
        }
        return $this->redirectToRoute('group_index');
    }
}
