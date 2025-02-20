<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class IndexController extends AbstractController
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private ProductRepository $productRepository
    ) {
    }
    
    #[Route('/', name: 'app_index', methods: ['GET'])]
    public function index(): Response
    {
        $featuredProducts = $this->productRepository->findBy(['isActive' => true], ['createdAt' => 'DESC'], 4);

        $categoriesMan = $this->categoryRepository->findByGroup('homme');
        $categoriesWoman = $this->categoryRepository->findByGroup('femme');
        $categoriesChild = $this->categoryRepository->findByGroup('enfant');

        return $this->render('index/index.html.twig', [
            'featuredProducts' => $featuredProducts,
            'categories_man' => $categoriesMan,
            'categories_woman' => $categoriesWoman,
            'categories_child' => $categoriesChild,
        ]);
    }
}
