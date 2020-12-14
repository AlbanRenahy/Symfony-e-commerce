<?php

namespace App\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function add(int $id)
    {
        // 1. Find the cart in session
        // 2. If doesn't exist, take an empty array
        $cart = $this->session->get('cart', []);

        // 3. See if product(id) already exist in array
        // 4. If it's the case, simply increase the quantity
        // 5. If not, add the product with quantity 1
        if (array_key_exists($id, $cart)) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        // 6. Register the updated array in session
        $this->session->set('cart', $cart);
    }

    public function remove(int $id) 
    {
        $cart = $this->session->get('cart', []);

        unset($cart[$id]);

        $this->session->set('cart', $cart);
    }

    public function decrement(int $id)
    {
        $cart=$this->session->get('cart', []);

        if(!array_key_exists($id, $cart)){
            return;
        }

        // If the product is set to 1, it needs to be deleted
        if($cart[$id] === 1){
            $this->remove($id);
            return;
        }

        // If the product is set to more than 1, it needs to be decremented
        $cart[$id]--;

        $this->session->set('cart', $cart);
    }

    public function getTotal(): int
    {
        $total = 0;

        foreach ($this->session->get('cart', []) as $id => $qty) {
            $product = $this->productRepository->find($id);

            if (!$product) {
                continue;
            }

            $total += $product->getPrice() * $qty;
        }

        return $total;
    }

    public function getDetailedCartItems(): array
    {
        $detailedCart = [];

        foreach ($this->session->get('cart', []) as $id => $qty) {
            $product = $this->productRepository->find($id);

            if (!$product) {
                continue;
            }
            $detailedCart[] = new CartItem($product, $qty);
        }

        return $detailedCart;
    }
}
