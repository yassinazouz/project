<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategorieController extends AbstractController
{
    #[Route('/admin/categorie', name: 'admin_categorie')]
    public function index(CategorieRepository $rep): Response
    {
        $categories = $rep->findAll();
        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/admin/categorie/create', name: 'admin_categorie_create')]
    public function create(EntityManagerInterface $em, Request $request): Response
    {

        $categorie = new Categorie();
        //creation d'un objet formulaire

        $form = $this->createForm(CategorieType::class, $categorie);

        //Affichage du formulaire
        return $this->render('categorie/create.html.twig', [
            'f' => $form,
        ]);
    }
}
