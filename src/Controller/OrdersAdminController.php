<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Repository\OrdersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]

class OrdersAdminController extends AbstractController
{
    #[Route('/orders/admin', name: 'app_orders_admin')]
    public function ordersAdmin(OrdersRepository $od): Response
    {
        $orders = $od->findAll();
        return $this->render('orders_admin/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/orders/update-etat/{id}', name: 'update_etat', methods: ['POST'])]
    public function updateEtat(Request $request, Orders $order, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $submittedToken = $request->request->get('_token');
        if ($this->isCsrfTokenValid('update_etat'.$order->getId(), $submittedToken)) {
            $newEtat = $request->request->get('etat');
            if (in_array($newEtat, ['En cours', 'Completée'])) {
                $order->setEtat($newEtat);
                $entityManager->flush();

                $this->addFlash('success', 'Etat updated successfully!');
            } else {
                $this->addFlash('error', 'Invalid etat value!');
            }
        } else {
            $this->addFlash('error', 'Invalid CSRF token!');
        }

        return $this->redirectToRoute('app_orders_admin');
    }
}
