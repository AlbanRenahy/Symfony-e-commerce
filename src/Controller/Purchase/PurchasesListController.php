<?php

namespace App\Controller\Purchase;

use Twig\Environment;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PurchasesListController extends AbstractController
{
    /**
     * @Route("/purchases", name="purchase_index")
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour accéder à vos commandes")
     */
    public function index()
    {
        // 1. Being sure that the user is connected (if not, redirect to homepage)
        /**
         * @var User
         */
        $user = $this->getUser();

        if (!$user) {
            throw new AccessDeniedException("Vous devez être connecté pour accéder à vos commandes");
        }

        // 2. We want to know who is connected
        // 3. Pass connected user to Twig to display purchases
        return $this->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);
    }
}
