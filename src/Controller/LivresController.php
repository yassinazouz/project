<?php

namespace App\Controller;

use App\Entity\Livres;
use App\Form\LivreType;
use App\Repository\LivresRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
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

    #[Route('/admin/livres/add', name: 'admin_livre_add')]
    public function add(EntityManagerInterface $em, Request $request): Response
    {
        $livre = new Livres();
        //creation d'un objet formulaire
        $form = $this->createForm(LivreType::class, $livre);
        // Récuperation et traitement des donnes
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid())
        {
            $em->persist($livre);
            $em->flush();
            return $this->redirectToRoute('app_admin_livres');
        }
        //Affichage du formulaire
        return $this->render('livres/add.html.twig', [
            'f' => $form,
        ]);
    }
    // créer une méthode update qui permet en connaissant id du livre de modifier son pris
    #[Route('/admin/livres/update/{id}', name: 'app_admin_livres_update')]
    public function update(EntityManagerInterface $em, Livres $livre): Response
    {
        $livre->setTitre('Titre du livre 10')
            ->setEditeur('Editeur 1')
            ->setISBN('111.1111.1111.1235')
            ->setPrix(200)
            ->setDateEdition(new \DateTime('01-01-2024'))
            ->setSlug('titre-du-livre-10')
            ->setResume('hfjhgdkfhfklgfdlkjgjgfmjgfgfjgjgbkjbfl,gj');
        $em->persist($livre);
        $em->flush();
        dd($livre);
        //return $this->render('livres/create.html.twig', [
        //   'livre' => $livre,
        // ]);
        return $this->redirectToRoute('admin_livres');
    }
}
