<?php

namespace App\Controller;

use App\Entity\Offres;
use App\Form\OffreType;
use App\Repository\OffresRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class OffresController extends AbstractController
{
    #[Route('/admin/Offres', name: 'app_admin_Offres')]

    public function index(OffresRepository $rep): Response
    {

        // $Offres = $rep->findGreaterThan(100);
        //dd($Offres);

        $Offres = $rep->findAll();
        //dd($Offres);

        return $this->render('Offres/index.html.twig', [
            'Offres' => $Offres,
        ]);
    }
    #[Route('/admin/Offres/{id<\d+>}', name: 'app_admin_Offres_show')]
    public function show(Offres $Offre): Response
    {
        //ParamConverter
        return $this->render('Offres/show.html.twig', [
            'Offre' => $Offre,
        ]);
    }
    #[Route('/admin/Offres/create', name: 'app_admin_Offres_create')]
    public function create(EntityManagerInterface $em): Response
    {
        $Offre1 = new Offres();
        $Offre1->setAuteur('auteur 1')
            ->setDateEdition(new \DateTime('01-01-2023'))
            ->setTitre('Titre 4')
            ->setResume('jhgkjhkjhlhdjfjfdgpghkgmgbkmgblkgm')
            ->setSlug('titre-4')
            ->setPrix(200)
            ->setEditeur('Eni')
            ->setISBN('111.1111.1111.1115')
            ->setImage('https://picsum.photos/300');
        $Offre2 = new Offres();
        $Offre2->setAuteur('auteur 1')
            ->setDateEdition(new \DateTime('01-01-2023'))
            ->setTitre('Titre 4')
            ->setResume('jhgkjhkjhlhdjfjfdgpghkgmgbkmgblkgm')
            ->setSlug('titre-4')
            ->setPrix(200)
            ->setEditeur('Eni')
            ->setISBN('111.1111.1111.1115')
            ->setImage('https://picsum.photos/300');
        $em->persist($Offre1);
        $em->persist($Offre2);
        $em->flush();
        dd($Offre1);
    }
    #[Route('/admin/Offres/delete/{id}', name: 'app_admin_Offres_delete')]
    public function delete(EntityManagerInterface $em, Offres $Offre): Response
    {

        $em->remove($Offre);
        $em->flush();
        dd($Offre);
    }

    #[Route('/admin/Offres/add', name: 'admin_Offre_add')]
    public function add(EntityManagerInterface $em, Request $request): Response
    {
        $Offre = new Offres();
        //creation d'un objet formulaire
        $form = $this->createForm(OffreType::class, $Offre);
        // Récuperation et traitement des donnes
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($Offre);
            $em->flush();
            return $this->redirectToRoute('app_admin_Offres');
        }
        //Affichage du formulaire
        return $this->render('Offres/add.html.twig', [
            'f' => $form,
        ]);
    }
    // créer une méthode update qui permet en connaissant id du Offre de modifier son pris
    #[Route('/admin/Offres/update/{id}', name: 'app_admin_Offres_update')]
    public function update(EntityManagerInterface $em, Offres $Offre): Response
    {
        $Offre->setTitre('Titre du Offre 10')
            ->setEditeur('Editeur 1')
            ->setISBN('111.1111.1111.1235')
            ->setPrix(200)
            ->setDateEdition(new \DateTime('01-01-2024'))
            ->setSlug('titre-du-Offre-10')
            ->setResume('hfjhgdkfhfklgfdlkjgjgfmjgfgfjgjgbkjbfl,gj');
        $em->persist($Offre);
        $em->flush();
        dd($Offre);
        //return $this->render('Offres/create.html.twig', [
        //   'Offre' => $Offre,
        // ]);
        return $this->redirectToRoute('admin_Offres');
    }

    #[Route('/image-good', name: 'app_Offres', methods: ['GET'])]
    public function imagesGood(OffresRepository $OffresRepository, EntityManagerInterface $entityManager, Request $request): Response
    {

        $images = array("https://cdn.codegym.cc/images/article/adf7ede4-5356-485c-8cdf-561b75da2685/512.jpeg","https://th.bing.com/th/id/R.62d8ea821d604e77a3492cdc68deaec3?rik=w%2bhYV7q7KPil%2fw&pid=ImgRaw&r=0","https://th.bing.com/th/id/OIP.2fJsV7bQPtImZFJBKgWPaAAAAA?rs=1&pid=ImgDetMain");
        $Offres = $OffresRepository->findAll();
        foreach ($Offres as $Offre) {
            $randomImageKey = array_rand($images);
            $randomImage = $images[$randomImageKey];
            $Offre->setImage($randomImage);
            $entityManager->persist($Offre);
        }
        $entityManager->flush();
        return new Response("all good");
    }
}
