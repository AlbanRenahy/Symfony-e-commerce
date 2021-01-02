<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Cart\CartService;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchasePaymentSuccessController extends AbstractController {

    /**
     * @Route("/purchase/terminates/{id}", name="purchase_payment_success")
     * @IsGranted("ROLE_USER")
     */
    public function success($id, PurchaseRepository $purchaseRepository, EntityManagerInterface $em, CartService $cartService) {

        //1. It take the purchase
        $purchase = $purchaseRepository->find($id);

        if(!$purchase  || 
        ($purchase && $purchase->getUser() !== $this->getUser() || 
        ($purchase && $purchase->getStatus() === Purchase::STATUS_PAID))) {
            $this->addFlash('warning', "La commande n'existe pas");
            return $this->redirectToRoute("purchase_index");
        }

        // 2. Passing to "PAID" status
        $purchase->setStatus(Purchase::STATUS_PAID);
        $em->flush();

        // 3. Empty the basket
        $cartService->empty();

        // 4. Redirect to command list with flash message
        $this->addFlash('success', "La commande a été payée et confirmée");

        return $this->redirectToRoute("purchase_index");

    }
}