<?php

namespace App\Controller\Admin;

use App\Entity\ProductVariant;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductVariantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductVariant::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste des %entity_label_plural%')
            ->setPageTitle('new', 'Créer une nouvelle variante')
            ->setPageTitle('detail', fn(ProductVariant $productVariant) => sprintf(
                'La variante <b>%s</b>', $productVariant->getSku(), $productVariant->getProduct()
            ))
            ->setPageTitle('edit', fn(ProductVariant $productVariant) => sprintf(
                'Modifier la variante <b>%s</b>', $productVariant->getSku(), $productVariant->getProduct()
            ))
            ->setEntityLabelInSingular('Variante')
            ->setEntityLabelInPlural('Variantes')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        // Is active
        yield BooleanField::new('isActive', 'Disponible');

        // Product
        yield AssociationField::new('product', 'Produit')
            ->setCrudController(ProductCrudController::class)
            ->setHelp('Sélectionner le produit pour lequel vous voulez créer une variante')
            ->setRequired(true)
            ->autocomplete()
        ;

        // Price
        yield MoneyField::new('price', 'Prix')->setCurrency('EUR');

        // Stock
        yield IntegerField::new('stock', 'Stock');

        // SKU
        yield TextField::new('sku', 'SKU')->setDisabled(true)->setHelp('Généré automatiquement (ex: TSHIRT-ADIDAS-ROUGE-XL-H)');

        // Size
        yield AssociationField::new('size', 'Taille')->setCrudController(SizeCrudController::class)->autocomplete();

        // Color
        yield AssociationField::new('colors', 'Couleurs')
            ->setCrudController(ColorCrudController::class)
            ->setFormTypeOptions([
                'by_reference' => false, 
                'multiple' => true
            ])
            ->formatValue(function($value, $entity) {
                if ($entity->getColors()) {
                    // Retrieve color names and concatenate them
                    return implode(', ', $entity->getColors()->map(fn($color) => $color->getName())->toArray());
                }
                return '';
            })->autocomplete()
        ;

        // Material
        yield AssociationField::new('materials', 'Matériaux')
            ->setCrudController(MaterialCrudController::class)
            ->setFormTypeOptions([
                'by_reference' => false, 
                'multiple' => true
            ])
            ->formatValue(function($value, $entity) {
                if ($entity->getMaterials()) {
                    // Retrieve material names and concatenate them
                    return implode(', ', $entity->getMaterials()->map(fn($material) => $material->getName())->toArray());
                }
                return '';
            })->autocomplete()
        ;
    }
}
