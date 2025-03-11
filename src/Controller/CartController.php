<?php

namespace App\Controller;

use App\Entity\Offres;
use App\Repository\OffresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cart', name: 'app_cart_')]
class CartController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, OffresRepository $Offresrep )
    {
        $panier = $session->get('panier',[]);
        //dd($panier);
        $data = [];
        $total = 0;
        foreach($panier as $id => $quantite){
            $Offre = $Offresrep->find($id);

            $data[]= [
                'Offre' => $Offre,
                'quantite' => $quantite

            ];
            $total += $Offre->getPrix() * $quantite;
    }
    // dd($data)

    return $this->render('cart/index.html.twig', compact('data' , 'total')); 

}

    #[Route('/add/{id}', name: 'add')]
    public function add(Offres $Offre, SessionInterface $session )
    {
        $id = $Offre->getId();
        $panier = $session->get('panier',[]);
        if(empty($panier[$id])){
            $panier[$id] = 1;
        }
        else
        {   
            $panier[$id]++;
        }
        $session->set('panier',$panier);
        return $this->redirectToRoute('app_cart_index');
         //dd($session);

        
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function remove(Offres $Offre, SessionInterface $session)
{
    $id = $Offre->getId();
    $panier = $session->get('panier', []);

    if (!empty($panier[$id])) {
        if ($panier[$id] > 1) {
            $panier[$id]--;
        } else {
            unset($panier[$id]);
        }
    } else {
        unset($panier[$id]);
    }
    $session->set('panier', $panier);
    return $this->redirectToRoute('app_cart_index');
}

#[Route('/delete/{id}', name: 'delete')]
public function delete(Offres $Offre, SessionInterface $session)
{
$id = $Offre->getId();
$panier = $session->get('panier', []);

if (!empty($panier[$id])) {

    unset($panier[$id]);
}
$session->set('panier', $panier);
return $this->redirectToRoute('app_cart_index');
}

#[Route('/vider', name: 'vider')]
public function vider (SessionInterface $session)
{
    $session->remove('panier');
    return $this->redirectToRoute('app_cart_index');

}


}