<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

class UtilisateurController extends AbstractController
{
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/gestion/utilisateurs", name="site.utlisateurs")
     */
    public function utilisateurs()
    {
        return new Response("Mouais");
    }

    /**
     * @Route("/gestion/utilisateurs/ajout", name="site.utlisateurs.ajout")
     */
    public function utilisateurs_ajout(Utilisateur $utilisateur = null, Request $request)
    {
        if($utilisateur == null){
            $utilisateur = new Utilisateur();
        }

        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $utilisateur->setCreeA(new \DateTime());

            $this->em->persist($utilisateur);
            $this->em->flush();

            return $this->redirectToRoute("site.utilisateurs");
        }

        return $this->render('utilisateur/ajout.html.twig', [
            'formulaire' => $form->createView(),
            'erreurs' => $form->getErrors()
        ]);
    }
}
