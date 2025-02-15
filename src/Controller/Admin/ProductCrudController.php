<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Enum\GenderEnum;
use App\Form\ProductVariantType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['isActive' => 'DESC', 'name' => 'ASC'])
            ->setPageTitle('index', 'Liste des %entity_label_plural%')
            ->setPageTitle('new', 'Créer un nouveau produit')
            ->setPageTitle('edit', fn (Product $product) => sprintf(
                'Modifier le produit <b>%s</b>', $product->getName()
            ))
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        // Is active
        yield BooleanField::new('isActive', 'Disponible');

        // Date
        yield DateField::new('createdAt', 'Date de sortie')->onlyOnDetail();

        // Gender (ENUM)
        yield ChoiceField::new('gender', 'Genre')->setColumns(6)
            ->setFormType(EnumType::class)
            ->setFormTypeOptions([
                'class' => GenderEnum::class,
                'choice_label' => fn (GenderEnum $gender): string => $gender->getLabel(),
            ])
        ;

        // Display first image
        yield ImageField::new('firstImage', 'Image')->setBasePath('/uploads/products')->hideOnForm();
        
        // Images associated
        yield AssociationField::new('images', 'Images associées')->hideOnForm()->hideOnIndex();

        // Brand
        yield AssociationField::new('brand', 'Marque')->setColumns(6)->autocomplete();

        // Name
        yield TextField::new('name', 'Nom du produit')->setColumns(6);

        // Slug
        yield SlugField::new('slug', 'Slug du produit (URL)')->setTargetFieldName('name')->setColumns(6)->hideOnIndex();

        // Categories
        yield AssociationField::new('categories', 'Catégories')->setColumns(6)
            ->formatValue(function($value, $entity) {
                if ($entity->getCategories()) {
                    // Retrieve category names and concatenate them
                    return implode(', ', $entity->getCategories()->map(fn ($category) => $category->getName())->toArray());
                }
                return ''; // Return an empty string if there are no categories
            })->autocomplete()
        ;

        // Tags
        yield AssociationField::new('tags', 'Tags')->setColumns(6)
            ->formatValue(function($value, $entity) {
                if ($entity->getTags()) {
                    // Retrieve tag names and concatenate them
                    return implode(', ', $entity->getTags()->map(fn ($tag) => $tag->getName())->toArray());
                }
                return '';
            })->autocomplete()
        ;

        // Description
        yield TextareaField::new('description', 'Description')->setColumns(6)->hideOnIndex();

        // Variants (association) - Is active, Price, stock, sku, size, color, material
        yield CollectionField::new('variants', 'Variants')->setColumns(6)
            ->setEntryType(ProductVariantType::class)
            ->setFormTypeOptions([
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ])->setHelp('Ajouter des variantes')->hideOnIndex()
        ;
    }
}
