<?php

namespace App\Controller;

use App\Service\EmailBuilder;
use App\Repository\UserRepository;
use App\Form\ResetPasswordRequestFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

#[Route('/resetpassword')]
class ResetPasswordController extends AbstractController
{

    // RESET PASSWORD 
    #[Route('/request', name: 'request_reset_password')]
    public function resetPassword(
        Request $request,
        UserRepository $userRepository,
        TokenGeneratorInterface $tokenGenerator,
        EmailBuilder $emailBuilder
    ): Response {

        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $userRepository->findOneBy([
                'email' => $form->get('email')->getData()
            ]);

            if (!$user) {
                return $this->redirectToRoute('login');
            } else {
                $token = $tokenGenerator->generateToken();
                $resetpasswordurl = $this->generateUrl(
                    'reset_reset_password',
                    ['token' => $token],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );

                //dd($resetpasswordurl);
                //dd($user);
                //$user = $user->getEmail();
                $emailBuilder->resetPassword($user, $resetpasswordurl);

                $this->addFlash('success', "Your request has been sent successfully !");

                return $this->redirectToRoute('home');
            }
        }

        return $this->render('reset_password/request.html.twig', [
           // 'requestPassForm' => $form->createView(),
          // 'controller_name' => 'ResetPasswordController',
           'requestForm' => $form->createView(),
        ]);
        
    }

    /**
     * Validates and process the reset URL that the user clicked 
     */
    #[Route('/reset/{token}', name: 'reset_reset_password')]
    public function reset(Request $request, UserPasswordHasherInterface $passwordHasher, string $token = null): Response
    {
        if ($token) {
            // We store the token in session and remove it from the URL, to avoid the URL being
            // loaded in a browser and potentially leaking the token to 3rd party JavaScript.
            $this->storeTokenInSession($token);
            
            return $this->redirectToRoute('reset_password');
        }

        $token = $this->getTokenFromSession();
        if (null === $token) {
            throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
        }

        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash('reset_password_error', sprintf(
                '%s - %s',
                $translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_VALIDATE, [], 'ResetPasswordBundle'),
                $translator->trans($e->getReason(), [], 'ResetPasswordBundle')
            ));

            return $this->redirectToRoute('app_forgot_password_request');
        }

        // The token is valid; allow the user to change their password.
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // A password reset token should be used only once, remove it.
            $this->resetPasswordHelper->removeResetRequest($token);

            // Encode(hash) the plain password, and set it.
            $encodedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPassword($encodedPassword);
            $this->entityManager->flush();

            // The session is cleaned up after the password has been changed.
            $this->cleanSessionAfterReset();

            return $this->redirectToRoute('login');
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);

    }




    //use ResetPasswordControllerTrait;

    // public function __construct(
    //     private ResetPasswordHelperInterface $resetPasswordHelper,
    //     private EntityManagerInterface $entityManager
    // ) 
    // {
    //}

    // #[Route('/', name: 'reset_password')]
    // public function request(string $emailFormData,): Response
    // {

    //     $user = $this->entityManager->getRepository(User::class)->findOneBy([
    //         'email' => $emailFormData,
    //     ]);

    //     try {
    //         $resetToken = $this->resetPasswordHelper->generateResetToken($user);
    //     } catch (ResetPasswordExceptionInterface $e) {
    //         return $this->redirectToRoute('login');
    //     }        


    //     $emailBuilder->resetPassword($user);

    //     return $this->render('reset_password/index.html.twig', [
    //         'controller_name' => 'ResetPasswordController',
    //     ]);
    // }
}
