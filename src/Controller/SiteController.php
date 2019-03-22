<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AstreinteRepository;
use App\Entity\NotInDatabase\Week;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Astreinte;
use App\Entity\BasicFunction;
use App\Entity\NotInDatabase\Weeks;

class SiteController extends AbstractController
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
     * @Route("/", name="site.home")
     */
    public function index()
    {
        return $this->render('site/index.html.twig', );
    }
    
    /**
     * @Route("/display", name="site.display")
     */
    public function display(AstreinteRepository $repo)
    {
        // echo date('F n', strtotime('2010-W50'));
        $year = date("Y");
        $astreintes = $repo->findByYear($year);
        $weeks = new Weeks($astreintes, intval($year));
        
        return $this->render('site/display.html.twig', [
            "weeks" => $weeks->test()
        ]);
    }
}
