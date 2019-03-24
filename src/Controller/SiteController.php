<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AstreinteRepository;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\NotInDatabase\Weeks;
use App\Entity\Astreinte;
use App\Form\AstreinteType;
use App\Entity\NotInDatabase\Week;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UtilisateurRepository;
use Faker\Provider\zh_CN\DateTime;

class SiteController extends AbstractController
{
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ObjectManager $em)
    {
        setlocale(LC_TIME, "fr_FR");
        $this->em = $em;
    }
    
    /**
     * Affichage de qui est actuellement d'astreinte
     * 
     * @Route("/", name="site.home")
     */
    public function index()
    {
        return $this->render('site/index.html.twig', );
    }
    
    /**
     * Affichage du tableau de toutes les astreintes
     * 
     * @Route("/astreintes/{year}", name="site.astreintes")
     */
    public function astreintes($year = null, AstreinteRepository $repo)
    {
        // Définition de l'année des Astreintes à l'année actuelle si elle n'est pas renseignée
        if($year == null) $year = intval(date("Y"));

        $astreintes = $repo->findByYear($year);
        $weeks = new Weeks($astreintes, $year);
        
        return $this->render('site/astreintes.html.twig', [
            "year" => $year,
            "weeks" => $weeks->getByMonth()
        ]);
    }

    /**
     * Changement d'un utilisateur en charge de l'astreinte
     * 
     * @Route("/astreinte/{year}/{week_num}", name="site.astreinte")
     */
    public function astreinte($year, $week_num, Astreinte $astreinte = null, Request $request, AstreinteRepository $repo)
    {
        $date = new \DateTime();
        $date->setISODate($year, $week_num);
        $week = new Week($date);

        // if($astreinte == null) $astreinte = new Astreinte();
        
        if($astreinte == null){
            $astreinte = $repo->find(["annee"=>$year, "semaine"=>$week_num]);
            if($astreinte == null){
                $astreinte = new Astreinte();
            }
        }

        $form = $this->createForm(AstreinteType::class, $astreinte);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $astreinte->setAnnee($year)
                      ->setSemaine(intval($week_num));
            $this->em->persist($astreinte);
            $this->em->flush();

            return $this->redirectToRoute("site.astreintes", ["year" => $year]);
        }
        
        return $this->render('site/astreinte.html.twig', [
            "week" => $week,
            "form" => $form->createView()
        ]);
    }

    /**
     * Suppression de l'astreinte selectionnée
     * 
     * @Route("/astreinte/del/{year}/{week_num}", name="site.astreinte.delete")
     */
    public function astreinte_del($year, $week_num, AstreinteRepository $repo)
    {        
        $astreinte = $repo->find(["annee"=>$year, "semaine"=>$week_num]);
        
        if($astreinte != null){
            $this->em->remove($astreinte);
            $this->em->flush();
        }

        return $this->redirectToRoute("site.astreintes", ["year" => $year]);
    }
    
    /**
     * Suppression de l'astreinte selectionnée
     * 
     * @Route("/astreintes/generate/{year}", name="site.astreintes.generate")
     */
    public function generate($year, UtilisateurRepository $utili_repo, AstreinteRepository $astr_repo)
    {
        $now = new \DateTime();
        $date = (new \DateTime())->setISODate($year, $now->format("W"));
        // Suppression de toutes les anciennes Astreintes
        $astreintes = $astr_repo->findByYear($year);
        foreach($astreintes as $astreinte){
            if($astreinte->getSemaine() > $now->format("W")){
                $this->em->remove($astreinte);
            }
        }        
        $this->em->flush();

        // Récupère tous les utilisateurs
        $utilisateurs =  $utili_repo->findAll();
        $i = mt_rand(0, count($utilisateurs));
        
        $date = new \DateTime($year."-01-01");

        // Ajoute toutes les Astreintes pour l'année 'year'
        while ($date->format("Y") == $year) {
            if($date > $now){
                if($i >= count($utilisateurs)) $i = 0;

                $astreinte = new Astreinte();
                $astreinte->setAnnee($year)
                          ->setSemaine(intval($date->format("W")))
                          ->setUtilisateur($utilisateurs[$i])
                ;
                $this->em->persist($astreinte);

                $i++;
            }
            $date->modify("+7 days");
        }
        $this->em->flush();

        return $this->redirectToRoute("site.astreintes", ["year" => $year]);
    }
}
