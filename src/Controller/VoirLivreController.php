<?php

namespace App\Controller;

use App\Entity\Livres;
use App\Repository\LivresRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CategorieRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

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
        $page = $request->query->getInt('page', 1);
        $limit = 8; // Number of results per page
    
        if ($searchTerm) {
            $queryBuilder = $livrep->createQueryBuilder('l')
                ->leftJoin('l.categorie', 'c')
                ->where('l.titre LIKE :searchTerm OR c.libelle LIKE :searchTerm OR l.Editeur LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
    
            $query = $queryBuilder->getQuery()
                ->setFirstResult($limit * ($page - 1))
                ->setMaxResults($limit)
                ->setHint(Paginator::HINT_ENABLE_DISTINCT, false);
    
            $paginator = new Paginator($query, false);
        } else {
            $paginator = $livrep->paginateLivres($page, $limit);
        }
    
        $maxPage = ceil(count($paginator) / $limit);
    
        return $this->render('voir_livre/index.html.twig', [
            'livres' => $paginator,
            'categories' => $categories,
            'page' => $page,
            'maxPage' => $maxPage,
        ]);
    }
    
    
}
