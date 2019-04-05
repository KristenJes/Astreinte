<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UtilisateurRepository;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
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
    public function utilisateurs(UtilisateurRepository $repo)
    {
        $utilisateur = $repo->findAll();

        return $this->render('utilisateur/index.html.twig', [
            'utilisateur' => $utilisateur]);
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

    /**
     * @Route("/gestion/utilisateurs/{id}/edition", name="site.utlisateurs.edition")
     */
    public function utilisateurs_edition(?int $id = null, Utilisateur $utilisateur = null, Request $request, UtilisateurRepository $repo)
    {
        if($id == null){
            return $this->redirectToRoute("site.utilisateurs");
        }
        
        if($utilisateur == null){
            $utilisateur = $repo->find($id);
        }

        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $utilisateur->setCreeA(new \DateTime());

            $this->em->persist($utilisateur);
            $this->em->flush();

            return $this->redirectToRoute("site.utilisateurs");
        }

        return $this->render('utilisateur/edition.html.twig', [
            'formulaire' => $form->createView(),
            'erreurs' => $form->getErrors()
        ]);
    }
}
