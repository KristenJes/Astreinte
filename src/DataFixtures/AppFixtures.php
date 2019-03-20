<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use \Faker\Factory;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Utilisateur;

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
        $faker = Factory::create("fr_FR");

        for($i = 1; $i <= 5; $i++){
            $utilisateur = new Utilisateur();
            $utilisateur->setNom($faker->lastName())
                        ->setPrenom($faker->firstName())    
                        ->setEmail($faker->email())
                        ->setMdp($this->encoder->encodePassword($utilisateur, 'password'))
                        ->setPhoto("http://lorempixel.com/800/400/people/")
                        ->setRoles(array("ROLE_USER"))
                        ->setNumero(intval($faker->mobileNumber()))
                        ->setCreeA(new \DateTime())
            ;

            $manager->persist($utilisateur);
        }

        $manager->flush();
    }
}
