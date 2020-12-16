<?php

namespace App\Controller\Purchase;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class PurchasesListController extends AbstractController
{
    protected $security;
    protected $router;
    protected $twig;

    public function __construct(Security $security, RouterInterface $router, Environment $twig)
    {
        $this->security = $security;
        $this->router = $router;
        $this->twig = $twig;
    }

    /**
     * @Route("/purchases", name="purchase_index")
     */
    public function index()
    {
        // 1. Being sure that the user is connected (if not, redirect to homepage)
        /**
         * @var User
         */
        $user = $this->security->getUser();

        if (!$user) {
            throw new AccessDeniedException("Vous devez être connecté pour accéder à vos commandes");
        }

        // 2. We want to know who is connected
        // 3. Pass connected user to Twig to display purchases
        $html = $this->twig->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);
        return new Response($html);
    }
}
