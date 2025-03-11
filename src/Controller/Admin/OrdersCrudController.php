<?php

// src/Controller/Admin/OrderCrudController.php

namespace App\Controller\Admin;

use App\Entity\Orders;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrdersCrudController extends AbstractCrudController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return Orders::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Order')
            ->setEntityLabelInPlural('Orders')
            ->setPageTitle("index","- Administration des Offres -")
            ->setPaginatorPageSize(10)
            ->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('ref')->onlyOnIndex(),
            DateTimeField::new('created_at')->onlyOnIndex(),
            ChoiceField::new('etat')
                ->setChoices(['En cours' => 'En cours', 'Completée' => 'Completée'])
                ->onlyOnForms(),
        ];
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW)
            ->disable(Action::DELETE);
    }

    #[Route('/admin/orders/update-etat/{id}', name: 'admin_orders_update_etat')]
    public function updateEtat(Request $request, Orders $order): Response
    {
        $submittedToken = $request->request->get('_token');
        if ($this->isCsrfTokenValid('update_etat'.$order->getId(), $submittedToken)) {
            $newEtat = $request->request->get('etat');
            if (in_array($newEtat, ['En cours', 'Completée'])) {
                $order->setEtat($newEtat);
                $this->entityManager->flush();

                $this->addFlash('success', 'Etat updated successfully!');
            } else {
                $this->addFlash('error', 'Invalid etat value!');
            }
        } else {
            $this->addFlash('error', 'Invalid CSRF token!');
        }

        return $this->redirectToRoute('admin_easyadmin', ['entity' => 'Orders']);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Orders', 'fa fa-list', Orders::class);
        // Add other menu items as needed
    }
}

