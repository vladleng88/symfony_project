<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\CodeGenerator;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, CodeGenerator $codeGenerator, Mailer $mailer, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $password = $passwordEncoder->encodePassword(
                $user,
                $user->getPlainPassword()
            );
            $user->setPassword($password);
            $user->setConfirmationCode($codeGenerator->getConfirmationCode());
            $user->setEnable(false);
            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();
            $mailer->sendConfirmationMessage($user);
            return $this->render('security/register_confirm.html.twig', [
                'user' => $user
            ]);
        }
        return $this->render('register/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirm/{code}", name="email_confirmation")
     */
    public function confirm(UserRepository $userRepository, string $code )
    {
        $user = $userRepository->findOneBy(['confirmationCode'=>$code]);
        if ($code == null ) {
            //throw $this->createNotFoundException('Uncorrect email validation');
        }
        $user->setEnable(true);
        $user->setConfirmationCode('');
    $em = $this->getDoctrine()->getManager();
    //$em->persist($user);
    $em->flush();
        return $this->render('security/account_confirm.html.twig', [
            'user' => $user,
        ]);
    }
}
