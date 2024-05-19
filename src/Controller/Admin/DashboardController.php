<?php
namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Entity\Livres;
use App\Entity\Orders;
use App\Entity\User;
use App\Repository\CategorieRepository;
use App\Repository\LivresRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DashboardController extends AbstractDashboardController
{
    private ChartBuilderInterface $chartBuilder;

    public function __construct(ChartBuilderInterface $chartBuilder)
    {
        $this->chartBuilder = $chartBuilder;
    }

    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function dashboard(CategorieRepository $categorieRepository, LivresRepository $livresRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $categories = $categorieRepository->findAll();
        $bookCounts = $categorieRepository->countBooksInCategories();

        $categNom = [];
        $annoncesCount = [];

        foreach ($bookCounts as $bookCount) {
            $categNom[] = $bookCount['category'];
            $annoncesCount[] = $bookCount['bookCount'];
        }
        
        $chart = $this->chartBuilder->createChart(Chart::TYPE_BAR);
        $chart->setData([
            'labels' => $categNom,
            'datasets' => [
                [
                    'label' => 'Nombre de livres',
                    'backgroundColor' => '#49B3DA',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $annoncesCount,
                ],
            ],
        ]);
        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 15,
                ],
            ],
        ]);
        return $this->render('admin/dashboard.html.twig', [
            'chart' => $chart,
        ]);
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
        yield MenuItem::linkToCrud('CategorieCrud', 'fas fa-list', Categorie::class);
        yield MenuItem::linkToCrud('OrdersCrud', 'fas fa-solid fa-truck-fast', Orders::class);
    }
}
