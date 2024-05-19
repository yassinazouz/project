<?php
namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Entity\Livres;
use App\Entity\Orders;
use App\Entity\User;
use App\Repository\CategorieRepository;
use App\Repository\LivresRepository;
use App\Repository\OrdersDetailsRepository;
use App\Repository\OrdersRepository;
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

    #[Route('/admin', name: 'admin_dashboard')]
    public function dashboard(CategorieRepository $categorieRepository, LivresRepository $livresRepository, OrdersDetailsRepository $ordersDetailsRepository, OrdersRepository $ordersRepository): Response
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
        
        $chart1 = $this->chartBuilder->createChart(Chart::TYPE_PIE);
        $chart1->setData([
            'labels' => $categNom,
            'datasets' => [
                [
                    'label' => 'Nombre de livres',
                    'backgroundColor' => ['#49B3DA', '#F79A3E', '#6FB148'],
                    
                    'data' => $annoncesCount,
                ],
            ],
        ]);
        $chart1->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 15,
                ],
            ],
        ]);
        $topSoldBooks = $ordersDetailsRepository->findTopSoldBooks();

        $bookTitles = [];
        $quantities = [];

        foreach ($topSoldBooks as $book) {
            $bookTitles[] = $book['titre'];
            $quantities[] = $book['quantity'];
        }

        $chart2 = $this->chartBuilder->createChart(Chart::TYPE_BAR);
        $chart2->setData([
            'labels' => $bookTitles,
            'datasets' => [
                [
                    'label' => 'Livre Le plus vendu entre 01/05/2024 et 31/05/2024',
                    'backgroundColor' => '#49B3DA',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $quantities,
                ],
            ],
        ]);
        $chart2->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 10,
                ],
            ],
        ]);
        $ordersByMonth = $ordersRepository->getCommandesNumberPerMonth(2024);

        $months = [];
        $orderCounts = [];
    
        foreach ($ordersByMonth as $data) {
            $months[] = date('F', mktime(0, 0, 0, $data['month'], 1)); // Convert month number to month name
            $orderCounts[] = $data['numCommandes'];
        }
    
        $chart3 = $this->chartBuilder->createChart(Chart::TYPE_LINE);
        $chart3->setData([
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'Nombre de commandes par mois',
                    'backgroundColor' => '#49B3DA',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $orderCounts,
                ],
            ],
        ]);
        $chart3->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => max($orderCounts) + 5, // Adjust max value
                ],
            ],
        ]);


        return $this->render('admin/dashboard.html.twig', [
            'chart1' => $chart1,
            'chart2' => $chart2,
            'chart3' => $chart3,
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