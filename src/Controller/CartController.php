<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function add($id, Request $request)
    {
        // 1. Find the cart in session
        // 2. If doesn't exist, take an empty array
        $cart = $request->getSession()->get('cart', []);

        // 3. See if product(id) already exist in array
        // 4. If it's the case, simply increase the quantity
        // 5. If not, add the product with quantity 1
        if(array_key_exists($id, $cart)){
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        
        // 6. Register the updated array in session
        $request->getSession()->set('cart', $cart);

        dd($request->getSession()->get('cart'));
    }
}
