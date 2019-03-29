<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AstreinteRepository;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\NotInDatabase\Weeks;
use App\Entity\Astreinte;
use App\Entity\Utilisateur;
use App\Form\AstreinteType;
use App\Entity\NotInDatabase\Week;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UtilisateurRepository;
use Faker\Provider\zh_CN\DateTime;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UtilisateurController extends AbstractController
{
    /**
     * Affichage de qui est actuellement d'astreinte
     * 
     * @Route("/gestion/utilisateur/ajout", name="utlisateur.ajout")
     */
    public function selection(AstreinteRepository $repo)
    {
        $utilisateur = New Utilisateur();
        $form = $this->createFormBuilder($utilisateur)
                     ->add('nom')

                     ->add('prenom')

                     ->add('email')

                     ->add('numero')

                     ->add('mdp', PasswordType::class )

                     ->add('photo')

                     ->getForm();

        return $this->render('utilisateur/ajout.html.twig', [
            'formulaire' => $form->createView()
        ]);
    }
}
