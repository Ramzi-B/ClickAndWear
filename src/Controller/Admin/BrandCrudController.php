<?php

namespace App\Controller\Admin;

use App\Entity\Brand;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BrandCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Brand::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['name' => 'ASC'])
            ->setPageTitle('index', 'Liste des %entity_label_plural%')
            ->setPageTitle('new', 'Ajouter une marque')
            ->setPageTitle('detail', fn (Brand $brand) => sprintf('Détails de La marque <b>%s</b>', $brand->getName()))
            ->setPageTitle('edit', fn (Brand $brand) => sprintf('Modifier la marque <b>%s</b>', $brand->getName()))
            ->setEntityLabelInSingular('Marque')
            ->setEntityLabelInPlural('Marques')
        ;
    }
    
    public function configureFields(string $pageName): iterable
    {
        // Id 
        yield IdField::new('id')->hideOnIndex()->hideOnForm();

        // Name
        yield TextField::new('name', 'Nom de la marque');

        // Slug
        yield SlugField::new('slug')->setTargetFieldName('name')->hideOnIndex();        
    }
    
}
