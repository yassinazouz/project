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
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/commandes', name: 'app_orders_')]
class OrdersController extends AbstractController
{
    #[Route('/checkout', name: 'checkout')]
    public function checkout(SessionInterface $session, LivresRepository $livresrep, EntityManagerInterface $em, $stripeSK): Response
    {
        Stripe::setApiKey($stripeSK);
    
        $panier = $session->get('panier', []);
        $total = 0;
    
        $lineItems = [];
        foreach ($panier as $id => $quantity) {
            $livre = $livresrep->find($id);
            $price = $livre->getPrix();
            $total += $price * $quantity;
    
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $livre->getTitre(),
                    ],
                    'unit_amount' => $price * 100,
                ],
                'quantity' => $quantity,
            ];
        }
    
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_orders_successURL', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('app_orders_cancelURL', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
    
        return $this->redirect($session->url, 303);
    }

    #[Route('/successURL', name: 'successURL')]
    public function successURL(SessionInterface $session, LivresRepository $livresrep, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $panier = $session->get('panier',[]);
        //dd($panier);
        if ($panier ===[])
        {
            $this->addFlash('message','Votre panier est vide');
            return $this->redirectToRoute('app_orders_cancelURL');

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

            $livre->setQte($livre->getQte() - $quantity);

            $orderDetails->setLivres($livre);
            $orderDetails->setPrice($price);
            $orderDetails->setQuantity($quantity);

            $order->addOrdersDetail($orderDetails);

        }

        $em->persist($order);
        $em->flush();

        $session->remove('panier');

        $this->addFlash('message','Commande créér avec succès');
        return $this->redirectToRoute('app_orders_successURL');


    

    }
    #[Route('/success', name: 'cancelURL')]
    public function cancelUrl(): Response
    {
        return $this->render('cart/cancel.html.twig', [
            
        ]);

    }
}

