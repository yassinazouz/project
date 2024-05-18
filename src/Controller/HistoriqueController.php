<?php

namespace App\Controller;

use App\Repository\OrdersDetailsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\OrdersRepository;

class HistoriqueController extends AbstractController
{
    #[Route('/historique', name: 'app_historique')]
    public function historique(OrdersRepository $ordersRepository , OrdersDetailsRepository $ordersDetailsRepository): Response
    {
        $user = $this->getUser();
        $orders = $ordersRepository->findBy(['users' => $user]);

        return $this->render('historique/historique.html.twig', [
            'orders' => $orders,
        ]);

        


    }
}
