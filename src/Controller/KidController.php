<?php

namespace App\Controller;

use App\Entity\Kid;
use App\Entity\User;
use App\Form\KidType;
use App\Entity\KidHasUser;
use App\Entity\GroupHasKid;
use App\Entity\EventExchange;
use App\Repository\KidRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

#[Route('/kid')]
class KidController extends AbstractController
{
    /**
     * KID INDEX 
     * @param KidRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/', name: 'kid_index', methods:['GET'])]
    public function index(KidRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $kids = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('kid/index.html.twig', [
            'kids' => $kids,
        ]);
    }

   /**
    * KID CREATION 
    *
    * @param Request $request
    * @param EntityManagerInterface $manager
    * @return Response
    */ 
    #[Route('/new', 'kid_new', methods: ['GET', 'POST'])]

    public function new(Request $request, KidRepository $repository,EntityManagerInterface $manager): Response
    {
        
        //dd($request); //receuille toute les informations dans la requeste
        $userId = $request->query->get('id');
        // dd($userId); verifie les informations contenue dans userId
        $kid = new Kid();
        $form = $this->createForm(KidType::class, $kid);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $kid = $form->getData();
            //$kid === $form->getData(); // récupère la donnée 
            $manager->persist($kid); // transfère dans la base de donnée
            $manager->flush();
            // je récupère l'id de kid //
            $kidHasUser = new KidHasUser();
            $kidHasUser->setKid($kid);

            $user = new User();
            $user = $manager->getRepository(User::class)->find($userId);
            //$user->setId($userId);
            $kidHasUser->setUser($user);
            //$userId = $request->query->get('id');
            $manager->persist($kidHasUser); // transfère dans la base de donnée
            //dd($kidHasUser);
            $manager->flush();
            
            
            $this->addFlash('success', 'Le profil enfant à bien était crée !');

            return $this->redirectToRoute('kid_index');
        }
        $list = $repository->findAll();
        return $this->render('kid/new.html.twig', [
            'form' => $form->createView(),
            'list'=> $list,
        ]);
    }
    #[Route('/edit/{id}', 'kid_edit', methods: ['GET', 'POST'])]
    /**
     * function edit kid
     * @param Kid $kid
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(Kid $kid, Request $request, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(KidType::class, $kid);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //dd($form->getData());
            $kid === $form->getData(); // récupère la donnée
            $manager->persist($kid); // transfère dans la base de donnée
            $manager->flush();

            $this->addFlash('success', 'Le profil enfant a bien était modifier !');

            return $this->redirectToRoute('kid_index');
        }
        return $this->render('kid/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', 'kid_delete', methods: ['GET'])]
    /**
     * this function delete kid
     * @param EntityManagerInterface $manager
     * @param Kid $kid
     * @return Response
     */
    public function delete(EntityManagerInterface $manager, Kid $kid): Response
    {
      
         $manager->remove($kid);
         $manager->flush();

         $this->addFlash('success', 'Le profil enfant a bien était supprimer !');

         return $this->redirectToRoute('kid_index');
    }
}
