<?php

namespace App\Controller;

use App\Entity\Livres;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\LivresRepository;

class VoirLivreController extends AbstractController
{
    #[Route('/voir/livre', name: 'app_voir_livre')]
    public function index(LivresRepository $rep): Response
    {

        $livres = $rep->findAll();
        return $this->render('voir_livre/index.html.twig', [
            'livres' => $livres,

        ]);
    }

    #[Route('/voir/livre/detail/{id<\d+>}', name: 'app_voir_livre_detail')]
    public function voirdetail(Livres $livre): Response
    {

        
        return $this->render('voir_livre/detail.html.twig', [
            'livres' => $livre,

        ]);
    }
}
