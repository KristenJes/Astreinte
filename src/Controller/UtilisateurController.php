<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UtilisateurRepository;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Entity\Astreinte;
use App\Form\AstreinteType;
use App\Repository\AstreinteRepository;
use App\Entity\NotInDatabase\Weeks;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @Route("/gestion/utilisateurs", name="site.utilisateurs")
     */
    public function utilisateurs(UtilisateurRepository $repo)
    {
        $utilisateur = $repo->findAll();

        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateur]);
    }

    /**
     * @Route("/gestion/utilisateurs/ajout", name="site.utlisateurs.ajout")
     */
    public function utilisateurs_ajout(Utilisateur $utilisateur = null, Request $request, UserPasswordEncoderInterface $encoder)
    {
        if($utilisateur == null){
            $utilisateur = new Utilisateur();
        }

        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $utilisateur->setCreeA(new \DateTime());
            $encoded = $encoder->encodePassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($encoded);

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
     * @Route("/gestion/utilisateurs/{id}/edition", name="site.utilisateurs.edition")
     */
    public function utilisateurs_edition(?int $id = null, Utilisateur $utilisateur = null, Request $request, UtilisateurRepository $repo, UserPasswordEncoderInterface $encoder)
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
            if($utilisateur->getPassword() == null){
                $encoded = $encoder->encodePassword($utilisateur, $utilisateur->getPassword());
                $utilisateur->setPassword($encoded);
            }

            $this->em->persist($utilisateur);
            $this->em->flush();

            return $this->redirectToRoute("site.utilisateurs");
        }

        return $this->render('utilisateur/edition.html.twig', [
            'formulaire' => $form->createView(),
            'erreurs' => $form->getErrors()
        ]);
    }


    /**
     * Suppression de l'astreinte selectionnée
     * 
     * @Route("/gestion/utilisateurs/del/{id}", name="site.utilisateurs.delete")
     */
    public function utilisateur_del($id, UtilisateurRepository $repo)
    {        
        $utilisateur = $repo->find($id);
        
        if($utilisateur != null){
            $this->em->remove($utilisateur);
            $this->em->flush();
        }

        return $this->redirectToRoute("site.utilisateurs");
    }




    /**
     * Affichage du tableau de toutes les astreintes
     * 
     * @Route("/gestion/utilisateurs/conseiller/{year}", name="site.utilisateurs.conseiller")
     */
    public function astreintes($year = null, AstreinteRepository $repo)
    {
        // Définition de l'année des Astreintes à l'année actuelle si elle n'est pas renseignée
        if($year == null) $year = intval(date("Y"));

        $astreintes = $repo->findByYear($year);
        $weeks = new Weeks($astreintes, 2019);
        
        return $this->render('utilisateur/astreintesConseiller.html.twig', [
            "year" => $year,
            "weeks" => $weeks->getByMonth()
        ]);
    }

    /**
     * Affichage de l'IHM du choix du mode de remplacement
     * 
     * @Route("/gestion/utilisateurs/conseiller/choixremplacement", name="site.utilisateurs.conseiller.choixremplacement")
     */
    public function choixremplacement()
    {
       
        // Définition de l'année des Astreintes à l'année actuelle si elle n'est pas renseignée
  
        return $this->render('utilisateur/ChoixRemplacement.html.twig', [


        ]);
    }


    /**
     * Affichage de l'IHM de permutation des conseiller
     * 
     * @Route("/gestion/utilisateurs/conseiller/permuation{year}", name="site.utilisateurs.conseiller.permutation")
     */
    public function permuation($year = null, AstreinteRepository $repo)
    {
       
        
        return $this->render('utilisateur/Permuation.html.twig', [

        ]);
    }


    /**
     * Affichage de l'IHM de remplacement des conseiller
     * 
     * @Route("/gestion/utilisateurs/conseiller/remplacement{year}", name="site.utilisateurs.conseiller.remplacement")
     */
    public function remplacement($year = null, AstreinteRepository $repo)
    {
       
        
        return $this->render('utilisateur/Remplacement.html.twig', [

        ]);
    }

}
