<?php

namespace App\Controller;

use App\Entity\Livres;
use App\Repository\LivresRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CategorieRepository;

class VoirLivreController extends AbstractController
{
    #[Route('/voir/livre', name: 'app_voir_livre')]
    public function index(LivresRepository $rep, Request $request , CategorieRepository $cat): Response
    {
        $page = $request->query->getInt('page',1);
        $limit = 9;
        $livres = $rep->paginateLivres($page , $limit);
        $maxPage = ceil(count($livres) / $limit);
        $categories = $cat->findAll();
        
    // Price filter parameters
    $priceMin = $request->query->get('price_min');
    $priceMax = $request->query->get('price_max');
        return $this->render('voir_livre/index.html.twig', [
            'livres' => $livres,
            'maxPage' => $maxPage,
            'page' =>$page,
            'categories' => $categories,
            'priceMin' => $priceMin,
            'priceMax' => $priceMax

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
        $limit = 10; // Number of results per page
    
        // Price filter parameters
        $priceRanges = $request->query->all('price_range');
        if (!is_array($priceRanges)) {
            $priceRanges = [$priceRanges];
        }
    
        // Debugging output
        dump($priceRanges);
    
        // Create query builder
        $queryBuilder = $livrep->createQueryBuilder('l')
            ->leftJoin('l.categorie', 'c')
            ->where('l.titre LIKE :searchTerm OR c.libelle LIKE :searchTerm OR l.Editeur LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%');
    
        // Add price range filtering
        if (!empty($priceRanges)) {
            $orX = $queryBuilder->expr()->orX();
            foreach ($priceRanges as $priceRange) {
                list($priceMin, $priceMax) = explode('-', $priceRange);
                $orX->add($queryBuilder->expr()->between('l.prix', ':priceMin' . $priceMin, ':priceMax' . $priceMax));
                $queryBuilder->setParameter('priceMin' . $priceMin, $priceMin);
                $queryBuilder->setParameter('priceMax' . $priceMax, $priceMax);
            }
            $queryBuilder->andWhere($orX);
        }
    
        $query = $queryBuilder->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
    
        $paginator = new Paginator($query, false);
        $maxPage = ceil(count($paginator) / $limit);
    
        return $this->render('voir_livre/index.html.twig', [
            'livres' => $paginator,
            'categories' => $categories,
            'page' => $page,
            'maxPage' => $maxPage,
            'searchTerm' => $searchTerm,
            'priceRanges' => $priceRanges,
        ]);
    }


    
}
