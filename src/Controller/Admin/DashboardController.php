<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Entity\Livres;
use App\Entity\Orders;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function index(): Response
    {

         return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Dashboard Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        
         yield MenuItem::linkToCrud('LivresCrud', 'fas fa-solid fa-book', Livres::class);
         yield MenuItem::linkToCrud('UserCrud', 'fas fa-regular fa-user', User::class);
         yield MenuItem::linkToCrud('CategorieCrud', 'fas  fa-list', Categorie::class);
         yield MenuItem::linkToCrud('OrdersCrud', 'fas  fa-solid fa-truck-fast', Orders::class);
    }
}
