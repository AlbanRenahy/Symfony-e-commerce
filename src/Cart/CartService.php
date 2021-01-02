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

    protected function getCart() : array 
    {
        return $this->session->get('cart', []);
    }

    protected function saveCart(array $cart)
    {
        $this->session->set('cart', $cart);
    }

    public function empty() {
        $this->saveCart([]);
    }

    public function add(int $id)
    {
        // 1. Find the cart in session
        // 2. If doesn't exist, take an empty array
        $cart = $this->getCart();

        // 3. See if product(id) already exist in array
        // 4. If it's the case, simply increase the quantity
        // 5. If not, add the product with quantity 1
        if (!array_key_exists($id, $cart)) {
            $cart[$id] = 0;
        }

        $cart[$id]++;

        // 6. Register the updated array in session
        $this->saveCart($cart);
    }

    public function remove(int $id) 
    {
        $cart = $this->getCart();

        unset($cart[$id]);

        $this->saveCart($cart);;
    }

    public function decrement(int $id)
    {
        $cart=$this->getCart();

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

        $this->saveCart($cart);
    }

    public function getTotal(): int
    {
        $total = 0;

        foreach ($this->getCart() as $id => $qty) {
            $product = $this->productRepository->find($id);

            if (!$product) {
                continue;
            }

            $total += $product->getPrice() * $qty;
        }

        return $total;
    }

    /**
     * @return CartItem[]
     */
    public function getDetailedCartItems(): array
    {
        $detailedCart = [];

        foreach ($this->getCart() as $id => $qty) {
            $product = $this->productRepository->find($id);

            if (!$product) {
                continue;
            }
            $detailedCart[] = new CartItem($product, $qty);
        }

        return $detailedCart;
    }
}
