<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProductController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository
    ) {
    }
    
    #[Route('/', name: 'app_product')]
    public function index(): Response
    {

        return $this->render('', []);
    }

    #[Route('/product/{slug}', name: 'app_product_show', requirements: ['slug' => Requirement::ASCII_SLUG], methods: ['GET'])]
    public function show(string $slug): Response
    {
        $product = $this->productRepository->findOneBy(['slug' => $slug]);

        if (!$product) {
            throw $this->createNotFoundException('Produit introuvable');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
