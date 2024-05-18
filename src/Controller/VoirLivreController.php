<?php

namespace App\Controller;

use App\Entity\Livres;
use App\Repository\LivresRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CategorieRepository;

class VoirLivreController extends AbstractController
{
    #[Route('/voir/livre', name: 'app_voir_livre')]
    public function index(LivresRepository $rep, Request $request): Response
    {
        $page = $request->query->getInt('page',1);
        $limit = 8;
        $livres = $rep->paginateLivres($page , $limit);
        $maxPage = ceil(count($livres) / $limit);
        
        return $this->render('voir_livre/index.html.twig', [
            'livres' => $livres,
            'maxPage' => $maxPage,
            'page' =>$page,

        ]);
    }

    #[Route('/voir/livre/detail/{id<\d+>}', name: 'app_voir_livre_detail')]
    public function voirdetail(Livres $livre): Response
    {

        
        return $this->render('voir_livre/detail.html.twig', [
            'livres' => $livre,

        ]);
    }

    #[Route('/voir/livre/titre', name: 'app_voir_livre_titre')]
    public function search(Request $request, LivresRepository $livrep, CategorieRepository $catrep): Response
    {
        $categories = $catrep->findAll();
        $searchTerm = $request->query->get('search');
    
        if ($searchTerm) {
            $query = $livrep->createQueryBuilder('l')
                ->leftJoin('l.categorie', 'c')
                ->where('l.titre LIKE :titre OR c.libelle LIKE :libelle or l.Editeur LIKE :Editeur ')
                ->setParameter('titre','%' . $searchTerm . '%' )
                ->setParameter('libelle', '%' . $searchTerm . '%')
                ->setParameter('Editeur', '%' . $searchTerm . '%')
                ->getQuery();
    
            $livres = $query->getResult();
        } else {
            // Si aucun terme de recherche n'est spécifié, afficher tous les livres
            $livres = $livrep->findAll();
        }
    
        return $this->render('voir_livre/index.html.twig', [
            'livres' => $livres,
            'categories' => $categories,
        ]);
        }
}
