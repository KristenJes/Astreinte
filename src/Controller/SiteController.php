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
    public function index(AstreinteRepository $repo)
    {
        $astreinte = $repo->findCurrent();

        return $this->render('site/index.html.twig', [
            'astreinte' => $astreinte
        ]);
    }

    /**
     * Affichage de qui est actuellement d'astreinte
     * 
     * @Route("/selection", name="site.selection")
     */
    public function selection(AstreinteRepository $repo)
    {

        return $this->render('site/selection.html.twig');
    }
    
    
    /**
     * Affichage du tableau de toutes les astreintes
     * 
     * @Route("/gestion/astreintes/{year}", name="site.astreintes")
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
     * @Route("/gestion/astreinte/{year}/{week_num}", name="site.astreinte")
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
     * @Route("/gestion/astreinte/del/{year}/{week_num}", name="site.astreinte.delete")
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
     * @Route("/gestion/astreintes/generate/{year}", name="site.astreintes.generate")
     */
    public function generate($year, UtilisateurRepository $utili_repo, AstreinteRepository $astr_repo)
    {
        $now = new \DateTime();
        $semaine = $year == date("Y") ? $now->format("W") : 1;
        $date = (new \DateTime())->setISODate($year, $semaine);
        // Suppression de toutes les anciennes Astreintes
        $astreintes = $astr_repo->findByYear($year, $semaine);

        foreach($astreintes as $astreinte){
            if($date > $now){
                $this->em->remove($astreinte);
            }
        } 
        $this->em->flush();

        // Récupère tous les utilisateurs
        $utilisateurs = $utili_repo->findByRole("USER"); // changer en LIKE ASTREINTEUR
        $i = mt_rand(0, count($utilisateurs));

        $interval = new \DateInterval('P1W');
        $period   = new \DatePeriod(new \DateTime($year."-01-04"), $interval, new \DateTime($year."-12-28"));
        foreach ($period as $dt) {
            if($dt > $now){                
                if($i >= count($utilisateurs)) $i = 0;
    
                $astreinte = new Astreinte();
                $astreinte->setAnnee($year)
                            ->setSemaine(intval($dt->format("W")))
                            ->setUtilisateur($utilisateurs[$i])
                ;
                $this->em->persist($astreinte);
    
                $i++;
            }
        }

        $this->em->flush();

        return $this->redirectToRoute("site.astreintes", ["year" => $year]);
    }
}
