<?php

namespace App\Controller;

use App\Entity\OrdersDetails;
use App\Repository\LivresRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Orders;

#[Route('/commandes', name: 'app_orders_')]
class OrdersController extends AbstractController
{
    #[Route('/ajout', name: 'add')]
    public function add(SessionInterface $session, LivresRepository $livresrep, EntityManagerInterface $em ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $panier = $session->get('panier',[]);
        //dd($panier);
        if ($panier ===[])
        {
            $this->addFlash('message','Votre panier est vide');
            return $this->redirectToRoute('app_voir_livre');

        }

        // CREER LA COMMANDE
        $order = new Orders();

        $order->setUsers($this->getUser());
        $order->setRef(uniqid());
        $order->setCreatedAt();

        foreach ($panier as $item => $quantity)
        {
            $orderDetails = new OrdersDetails(); 

            $livre = $livresrep->find($item);
            $price = $livre->getPrix();

            $orderDetails->setLivres($livre);
            $orderDetails->setPrice($price);
            $orderDetails->setQuantity($quantity);

            $order->addOrdersDetail($orderDetails);

        }

        $em->persist($order);
        $em->flush();

        $session->remove('panier');

        $this->addFlash('message','Commande créér avec succès');
        return $this->redirectToRoute('app_voir_livre');


    }
}
