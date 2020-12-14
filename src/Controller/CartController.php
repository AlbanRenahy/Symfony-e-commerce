<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id":"\d+"})
     */
    public function add($id, ProductRepository $productRepository, SessionInterface $session)
    {
        // 0. Does the product exist in database
        $product = $productRepository->find($id);

        if(!$product){
            throw $this->createNotFoundException("Le produit $id n'existe pas");
        }

        // 1. Find the cart in session
        // 2. If doesn't exist, take an empty array
        $cart = $session->get('cart', []);

        // 3. See if product(id) already exist in array
        // 4. If it's the case, simply increase the quantity
        // 5. If not, add the product with quantity 1
        if(array_key_exists($id, $cart)){
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        
        // 6. Register the updated array in session
        $session->set('cart', $cart);

        /** @var FlashBag */
        $flashBag = $session->getBag('flashes');

        // Add flash messages
        $flashBag->add('success', "Le produit a bien été ajouté au panier");

        return $this->redirectToRoute('product_show', [
            'category_slug' => $product->getCategory()->getSlug(),
            'slug' => $product->getSlug()
        ]);
    }
}
