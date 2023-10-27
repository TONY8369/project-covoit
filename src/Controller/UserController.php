<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Kid;
use App\Form\UserEditType;
use App\Form\UserType;
use App\Repository\KidRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/user')]
class UserController extends AbstractController
{

    // INDEX USER 
    #[Route('/', name: 'user_index', methods: ['GET'])]
    /**
     * This function list user
     * @param UserRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(UserRepository $userRepository,PaginatorInterface $paginator,Request $request): Response
    {
        $users = $paginator->paginate(
            $userRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );
        //dd($users);

        foreach ($users as $user) {
            $user->kids = $userRepository->findOneByIdJoinedToUser($user->getId());
        }
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    // USER CREATION 
    #[Route('/new', name: 'user_new', methods: ['GET', 'POST'])]
    public function new(Request $request,UserRepository $userRepository,UserPasswordHasherInterface $userPasswordHasher): Response 
    {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
                // remplace persist , flush //
                $userRepository->save($user, true);

                return $this->redirectToRoute('login');
            //}

            return $this->redirectToRoute('user_index');
        }
         
        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
            'userCreationForm' => $form->createView(),

        ]);
    }

    // USER EDIT 
    #[Route('edit/{id}', 'user_edit', methods: ['GET', 'POST'])]
    /**
     * this function edit à utilisateur
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(User $user, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user === $form->getData(); // récupère la donnée
            //dd($form->getData());

            $manager->persist($user); // transfère dans la base de donnée
            $manager->flush();

            $this->addFlash('success', 'Le profil utilisateur à bien était modifié !');

            return $this->redirectToRoute('user_index');
        }
        return $this->render('user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('kidEdition/{id}', 'kid_edit', methods: ['GET', 'POST'])]
    /**
     * this function edit à kid utilisateur
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function kidEdit(User $user, UserRepository $userRepository): Response
    {
        //liste des enfants ratacher par le user
        $userKids = $userRepository->findOneByIdJoinedToUser($user->getId());
        // id de l'user
        $userId = $user->getId();
        // premier élément de mon tableau
        $KidUn = array_values($userKids)[0];
        // deuxième élément de mon tableau
        $KidDeux = array_values($userKids)[1];
        //dd($array,$array2);
        //convertit un tableau en chaine de caractère //
        //dd(join(", ", $array));

         return $this->render('user/kidEdit.html.twig', [
            'userKids' => $userKids,
            'userId'=> $userId,
            'KidUn' => $KidUn,
            'KidDeux' => $KidDeux,

        ]);
    }

    #[Route('kidEdition_post/', 'kid_edit_post', methods: ['GET', 'POST'])]
    /**
     * this function edit à kid utilisateur
     * @param Kid $kid
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function kidEdits(Request $request, EntityManagerInterface $manager): Response
    {

            
           // Ont récupère l'id de kid
            $kidId = $request->get('id');
            //dd($kidId);
             // récupère les données firstname et lastname
            $firstnameArray = $request->get('firstname');
            $lastnameArray =  $request->get('lastname');
            // récupère le nombre de variable dans mon tableau //
            // $lengthUn = count($firstnameArray);
            // $lengthDeux = count($lastnameArray);
            //
            //dd($request);
                for ($i= 0; $i < count($firstnameArray)  ; $i++) {
                    $firstnameArray[$i];
                    $lastnameArray[$i];
                    $kidId[$i];

                    $kid = $manager->getRepository(Kid::class)->find($kidId[$i]);
                    //dd($kid);
                    // je récupère l'id de l'enfant relier au parent et je le set
                    //dd($kid->setId($kidId[$i]));
                    $kid->setId($kidId[$i]);
                    // je récupère le firstname modifier de l'enfant relier au parent et je le set
                    //dd($kid->setFirstname($firstnameArray[$i]));
                    $kid->setFirstname($firstnameArray[$i]);
                    // je récupère le lastname modifier de l'enfant relier au parent et je le set
                    //dd($kid->setLastname($lastnameArray[$i]));
                    $kid->setLastname($lastnameArray[$i]);

                    //dd($kid);
                    $manager->persist($kid); // transfère dans la base de donnée
                    $manager->flush();
                }

            // $kid->setFirstname($firstnameArray);
            // $kid->setLastname($lastnameArray);   
            // convertir les données en string
            // $postFirstname = join(" " , $request->get('firstname'));
            // $postLastname = join(" " , $request->get('lastname'));
            // regarde ce qui contien la request //
            
            //dd($kid);
            $manager->persist($kid); // transfère dans la base de donnée
            $manager->flush();

            $this->addFlash('success', 'Le profil enfant ratacher à bien était modifier !');

            return $this->redirectToRoute('user_index');
    }

    // USER DELETE 
    #[Route('/delete/{id}', 'user_delete', methods: ['GET'])]
    /**
     * This function delete user
     * @param EntityManagerInterface $manager
     * @param User $user
     * @return Response
     */
    public function delete(EntityManagerInterface $manager, User $user): Response
    {
        $manager->remove($user);
        $manager->flush();

        $this->addFlash('success', 'Le profil utilisateur à bien était supprimer !');

        return $this->redirectToRoute('user_index');
    }
}
