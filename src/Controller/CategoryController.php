<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/category/create", name="category_create")
     */
    public function create()
    {
        return $this->render('category/create.html.twig');
    }


    /**
     * @Route("/admin/category/{id}/edit", name="category_edit")
     *
     */
    public function edit($id, CategoryRepository $categoryRepository){

        $category = $categoryRepository->find($id);

        return $this->render('category/edit.html.twig', [
            'category' => $category,
        ]);
    }
}
