<?php

namespace App\Controller;

use App\Entity\Livres;
use App\Repository\LivresRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LivresController extends AbstractController
{
    #[Route('/admin/livres', name: 'app_admin_livres')]
    public function index(LivresRepository $rep): Response
    {
        // $livres = $rep->findGreaterThan(100);
        //dd($livres);

        $livres = $rep->findAll();
        //dd($livres);

        return $this->render('livres/index.html.twig', [
            'livres' => $livres,
        ]);
    }
    #[Route('/admin/livres/{id<\d+>}', name: 'app_admin_livres_show')]
    public function show(Livres $livre ): Response
    {
        //ParamConverter
        return $this->render('livres/show.html.twig', [
            'livre' => $livre,
        ]);
    }
    #[Route('/admin/livres/create', name: 'app_admin_livres_create')]
    public function create(EntityManagerInterface $em): Response
    {
        $livre1 = new Livres();
        $livre1->setAuteur('auteur 1')
            ->setDateEdition(new \DateTime('01-01-2023'))
            ->setTitre('Titre 4')
            ->setResume('jhgkjhkjhlhdjfjfdgpghkgmgbkmgblkgm')
            ->setSlug('titre-4')
            ->setPrix(200)
            ->setEditeur('Eni')
            ->setISBN('111.1111.1111.1115')
            ->setImage('https://picsum.photos/300');
        $livre2 = new Livres();
        $livre2->setAuteur('auteur 1')
            ->setDateEdition(new \DateTime('01-01-2023'))
            ->setTitre('Titre 4')
            ->setResume('jhgkjhkjhlhdjfjfdgpghkgmgbkmgblkgm')
            ->setSlug('titre-4')
            ->setPrix(200)
            ->setEditeur('Eni')
            ->setISBN('111.1111.1111.1115')
            ->setImage('https://picsum.photos/300');
        $em->persist($livre1);
        $em->persist($livre2);
        $em->flush();
        dd($livre1);
    }
    #[Route('/admin/livres/delete/{id}', name: 'app_admin_livres_delete')]
    public function delete(EntityManagerInterface $em, Livres $livre): Response
    {

        $em->remove($livre);
        $em->flush();
        dd($livre);
    }
    // créer une méthode update qui permet en connaissant id du livre de modifier son pris
}
