<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id":"\d+"})
     */
    public function add($id, ProductRepository $productRepository, CartService $cartService)
    {
        // 0. Does the product exist in database
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("Le produit $id n'existe pas");
        }

        $cartService->add($id);

        // Add flash messages
        $this->addFlash('success', "Le produit a bien été ajouté au panier");

        return $this->redirectToRoute('product_show', [
            'category_slug' => $product->getCategory()->getSlug(),
            'slug' => $product->getSlug()
        ]);
    }

    /**
     * @Route("/cart", name="cart_show")
     *
     * @return void
     */
    public function show(CartService $cartService)
    {
        $detailedCart = $cartService->getDetailedCartItems();

        $total = $cartService->getTotal();
        
        
        return $this->render('cart/index.html.twig', [
            'items' => $detailedCart,
            'total' => $total
        ]);
    }

    /**
     * @Route("/cart/delete/{id}", name="cart_delete", requirements={"id": "\d+"})
     *
     * @return void
     */
    public function delete($id, ProductRepository $productRepository, CartService $cartService)
    {
        $product = $productRepository->find($id);

        if(!$product) {
            throw $this->createNotFoundException("Le produit $id n'existe pas et ne peut pas être supprimé");
        }

        $cartService->remove($id);

        $this->addFlash("success", "Le produit a bien été supprimé du panier");

        return $this->redirectToRoute("cart_show");
    }
}
