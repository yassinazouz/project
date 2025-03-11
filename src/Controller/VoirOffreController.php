<?php

namespace App\Controller;

use App\Entity\Offres;
use App\Repository\OffresRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CategorieRepository;

class VoirOffreController extends AbstractController
{
    #[Route('/voir/Offre', name: 'app_voir_Offre')]
    public function index(OffresRepository $rep, Request $request , CategorieRepository $cat): Response
    {
        $page = $request->query->getInt('page',1);
        $limit = 9;
        $Offres = $rep->paginateOffres($page , $limit);
        $maxPage = ceil(count($Offres) / $limit);
        $categories = $cat->findAll();
        
    // Price filter parameters
    $priceMin = $request->query->get('price_min');
    $priceMax = $request->query->get('price_max');
    $priceRanges = $request->query->all('price_range');
        if (!is_array($priceRanges)) {
            $priceRanges = [$priceRanges];
        }
        return $this->render('voir_Offre/index.html.twig', [
            'Offres' => $Offres,
            'maxPage' => $maxPage,
            'page' =>$page,
            'categories' => $categories,
            'priceMin' => $priceMin,
            'priceMax' => $priceMax,
            'priceRanges' => $priceRanges,

        ]);
    }

    #[Route('/voir/Offre/detail/{id<\d+>}', name: 'app_voir_Offre_detail')]
    public function voirdetail(Offres $Offre): Response
    {

        
        return $this->render('voir_Offre/detail.html.twig', [
            'Offres' => $Offre,

        ]);
    }


    #[Route('/voir/Offre/titre', name: 'app_voir_Offre_titre')]
    public function search(Request $request, OffresRepository $Offrep, CategorieRepository $catrep): Response
    {
        $categories = $catrep->findAll();
        $searchTerm = $request->query->get('search');
        $page = $request->query->getInt('page', 1);
        $limit = 9; // Number of results per page
    
        // Price filter parameters
        $priceRanges = $request->query->all('price_range');
        if (!is_array($priceRanges)) {
            $priceRanges = [$priceRanges];
        }
    
        // Debugging output
        dump($priceRanges);
    
        // Create query builder
        $queryBuilder = $Offrep->createQueryBuilder('l')
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
    
        return $this->render('voir_Offre/index.html.twig', [
            'Offres' => $paginator,
            'categories' => $categories,
            'page' => $page,
            'maxPage' => $maxPage,
            'searchTerm' => $searchTerm,
            'priceRanges' => $priceRanges,
        ]);
    }


    
}
