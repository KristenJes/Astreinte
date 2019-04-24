<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use \Faker\Factory;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Utilisateur;
use App\Entity\Astreinte;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        set_time_limit(6000000); 
        ini_set("memory_limit", -1);
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // Utilisation de la classe Faker qui permet de générer des valeurs aléatoires
        $faker = Factory::create("fr_FR");
        // Liste de tous les utilisateurs aléatoirement générés
        $utilisateurs = array();


        for($i = 1; $i <= 10; $i++){
            // Créer un nouvel utilisateur
            $utilisateur = new Utilisateur();
            // Ajoute des données aléatoires dans l'utilisateur avec le mot de passe 'password'
            $utilisateur->setNom($faker->lastName())
                        ->setPrenom($faker->firstName())    
                        ->setEmail($faker->email())
                        ->setPassword($this->encoder->encodePassword($utilisateur, 'password'))
                        ->setPhoto("http://lorempixel.com/800/400/people/")
                        ->setRoles(array("ROLE_USER"))
                        ->setNumero('06' . $faker->randomNumber(8))
                        ->setCreeA(new \DateTime())
            ;
            $manager->persist($utilisateur);
            // Ajoute le nouvel utilisateur dans le tableau
            $utilisateurs[] = $utilisateur;
        }

        // Création des astreintes avec une date de début et de fin
        $date_start = new \DateTime("-2 years");
        $date_end = new \DateTime("+1 year");
        $val = 0;
        for($date = $date_start; $date < $date_end; $date->modify('next monday')){
            // 8 chances sur 10 d'ajouter une astreinte
            if($faker->boolean(80)){
                if($val >= count($utilisateurs)) $val = 0;
        
                // Créer une nouvelle astreinte
                $astreinte = new Astreinte();
                // Ajoute des données aléatoires dans l'astreinte
                $astreinte->setSemaine(intval($date->format("W")))
                            ->setAnnee(intval($date->format("Y")))
                            ->setUtilisateur($utilisateurs[$val])
                            ->setCommentaire($faker->boolean(15) ? $faker->text($faker->numberBetween(25, 240)) : null)
                ;
                $manager->persist($astreinte);
            }

            $val++;
        }

        // Ajout dans la base de données
        $manager->flush();
        // Fixtures ajoutées
    }
}
