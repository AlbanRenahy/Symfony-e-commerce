<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Form\CartConfirmationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class PurchaseConfirmationController
{
    protected $formFactory;
    protected $router;
    protected $security;
    protected $cartService;

    public function __construct(FormFactoryInterface $formFactory, RouterInterface $router, Security $security, CartService $cartService)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->security = $security;
        $this->cartService = $cartService;
    }


    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     */
    public function confirm(Request $request, FlashBagInterface $flashBag) {

        // 1. Read the form data
        // FormFactoryInterface / Request
        $form = $this->formFactory->create(CartConfirmationType::class);

        $form->handleRequest($request);
        // 2. If form isn't submitted: get out
        if(!$form->isSubmitted()) {
            // Message flash and then redirect (FlashBagInterface)
            $flashBag->add('warning', 'Vous devez remplir le formulaire de confirmation');
            return new RedirectResponse($this->router->generate('cart_show'));
        }

        // 3. If not connected:  get out (security)
        $user = $this->security->getUser();

        if(!$user) {
            throw new AccessDeniedException("Vous devez être connecté pour confirmer une commande");
        }
        
        // 4. If no product in cart: getout (CartService)
        $cartItems = $this->cartService->getDetailedCartItems();

        if (count($cartItems) === 0) {
            $flashBag->add('warning', 'Vous ne pouvez pas confirmer une commande avec un panier vide');
            return new RedirectResponse($this->router->generate('cart_show'));
        }

        // 5. We will create a purchase

        // 6. We will link it with connected user (Security)

        // 7. We will link her with products in cart

        // 8. We will register the pruchase (EntityManagerInterface)
    }
}