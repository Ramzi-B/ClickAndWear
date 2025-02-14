<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Entity\Size;
use App\Entity\Brand;
use App\Entity\Color;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Material;
use App\Entity\ProductImage;
use App\Entity\ProductVariant;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(ProductCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ClickAndWear Administration');
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_EDIT, Action::DETAIL)
            ->reorder(Crud::PAGE_DETAIL, [
                Action::INDEX,
                Action::DELETE,
                Action::EDIT,
            ])
            ->update(Crud::PAGE_DETAIL, Action::EDIT, 
                static function (Action $action) {
                    return $action->setIcon('fa fa-edit');
            })
            ->update(Crud::PAGE_DETAIL, Action::INDEX, 
                static function (Action $action) {
                    return $action->setIcon('fa fa-list');
            })
        ;
    }

    public function configureCrud(): Crud
    {
        return parent::configureCrud()
            ->setDateTimeFormat('medium', 'short')
            ->setPaginatorPageSize(10)
            ->setDefaultSort(['id' => 'DESC'])
        ;
    }

    public function configureMenuItems(): iterable
    {
        
        yield MenuItem::linkToUrl('Retour sur le site', 'fa-solid fa-house', $this->generateUrl('app_index'));
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-dashboard');
        
        yield MenuItem::section('Catalogue');

        //  Products
        yield MenuItem::subMenu('Produit', 'fa-solid fa-bag-shopping')->setSubItems([
            MenuItem::linkToCrud('Liste des produits', '', Product::class)->setController(ProductCrudController::class),
            MenuItem::linkToCrud('Ajouter', 'fa-solid fa-file-circle-plus', Product::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Variantes', 'fa fa-tags', ProductVariant::class)->setController(ProductVariantCrudController::class)
        ]);

        // Sizes
        yield MenuItem::subMenu('Taille', 'fa-solid fa-tag')->setSubItems([
            MenuItem::linkToCrud('Liste des tailles', 'fa-solid fa-tag', Size::class)->setController(SizeCrudController::class),
            MenuItem::linkToCrud('Ajouter', 'fa-solid fa-file-circle-plus', Size::class)->setAction(Crud::PAGE_NEW)
        ]);

        // Colors
        yield MenuItem::subMenu('Couleur', 'fa-solid fa-tag')->setSubItems([
            MenuItem::linkToCrud('Liste des couleurs', 'fa-solid fa-tag', Color::class)->setController(ColorCrudController::class),
            MenuItem::linkToCrud('Ajouter', 'fa-solid fa-file-circle-plus', Color::class)->setAction(Crud::PAGE_NEW)
        ]);

        // Materials
        yield MenuItem::subMenu('Matière', 'fa-solid fa-tag')->setSubItems([
            MenuItem::linkToCrud('Liste des matières', 'fa-solid fa-tag', Material::class)->setController(MaterialCrudController::class),
            MenuItem::linkToCrud('Ajouter', 'fa-solid fa-file-circle-plus', Material::class)->setAction(Crud::PAGE_NEW)
        ]);

        // Brands
        yield MenuItem::subMenu('Marque', 'fa-solid fa-tag')->setSubItems([
            MenuItem::linkToCrud('Liste des marques', 'fa-solid fa-tag', Brand::class)->setController(BrandCrudController::class),
            MenuItem::linkToCrud('Ajouter', 'fa-solid fa-file-circle-plus', Brand::class)->setAction(Crud::PAGE_NEW)
        ]);

        // Categories
        yield MenuItem::subMenu('Categorie', 'fa-solid fa-tag')->setSubItems([
            MenuItem::linkToCrud('Liste des categories', 'fa-solid fa-tags', Category::class),
            MenuItem::linkToCrud('Ajouter', 'fa-solid fa-plus', Category::class)->setAction(Crud::PAGE_NEW)
        ]);

        // Tags
        yield MenuItem::subMenu('Tag', 'fa-solid fa-tag')->setSubItems([
            MenuItem::linkToCrud('Liste des tags', 'fa-solid fa-tags', Tag::class),
            MenuItem::linkToCrud('Ajouter', 'fa-solid fa-plus', Tag::class)->setAction(Crud::PAGE_NEW)
        ]);

        // Images
        yield MenuItem::subMenu('Image', 'fa-solid fa-picture')->setSubItems([
            MenuItem::linkToCrud('Liste des images', 'fa-solid fa-picture', ProductImage::class),
            MenuItem::linkToCrud('Ajouter', 'fa-solid fa-plus', ProductImage::class)->setAction(Crud::PAGE_NEW)
        ]);
    }
}
