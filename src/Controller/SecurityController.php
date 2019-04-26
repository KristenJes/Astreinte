<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;

class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="security.register")
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new Utilisateur();        
        $form = $this->createForm(UtilisateurType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash)
                 ->setCreatedBy($user);
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('security.login');
        }
        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/login", name="security.login")
     */
    public function login(AuthenticationUtils $authenticationUtils, TranslatorInterface $translator)
    {        
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        // Notification
        $message = null;
        if($error){
            $message = $translator->trans($error->getMessageKey(), $error->getMessageData(), 'security');
        }
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $message
        ]);
    }
    /**
     * @Route("/logout", name="security.logout")
     */
    public function logout(){}
    /**
     * @Route("/me", name="site.me")
     */
    public function me(Request $request)
    {
        $form = $this->createForm(UtilisateurType::class, $this->getUser());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            return $this->redirectToRoute("site.event");
        }
        return $this->render('site/me.html.twig', [
            'form' => $form->createView()
        ]);
    }
}