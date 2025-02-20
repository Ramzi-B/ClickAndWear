<?php

namespace App\Controller;

use App\Enum\GenderEnum;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Requirement\Requirement;

final class CategoryController extends AbstractController
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private ProductRepository $productRepository
    ) {
    }
    
    #[Route('/category/{slug}', name: 'app_category', requirements: ['slug' => Requirement::ASCII_SLUG], methods: ['GET'])]
    public function index(string $slug): Response
    {
        $category = $this->categoryRepository->findOneBy(['slug' => $slug]);

        if (!$category) {
            throw $this->createNotFoundException('Catégorie introuvable');
        }

        $products = $category->getProducts()->toArray();
        
        return $this->render('category/index.html.twig', [
            'category' => $category,
            'products' => $products,
        ]);
    }

    #[Route('/category/{gender}/{slug}', name: 'app_category_show_by_gender', requirements: ['slug' => Requirement::ASCII_SLUG, 'gender' => '.+'], methods: ['GET'])]
    public function showByGender(string $gender, string $slug): Response
    {
        // Convert gender string to enum
        $genderEnum = GenderEnum::tryFrom($gender);

        if (!$genderEnum) {
            throw $this->createNotFoundException('Genre invalide.');
        }

        $category = $this->categoryRepository->findOneBy(['slug' => $slug]);

        if (!$category) {
            throw $this->createNotFoundException('Catégorie introuvable');
        }

        $products = $this->categoryRepository->findProductsByCategoryGroupAndGender($slug, $genderEnum);

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'products' => $products,
            'gender' => $genderEnum
        ]);
    }
}
