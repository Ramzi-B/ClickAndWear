<?php

namespace App\Controller\Admin;

use App\Entity\ProductImage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductImage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste des %entity_label_plural%')
            ->setPageTitle('new', 'Ajouter une image')
            ->setPageTitle('detail', fn(ProductImage $productImage) => sprintf(
                'L\'image de l\'article <b>%s</b>', $productImage->getProduct()
            ))
            ->setPageTitle('edit', fn (ProductImage $productImage) => sprintf(
                'Modifier l\'image de l\'article <b>%s</b>', $productImage->getProduct()
            ))
            ->setEntityLabelInSingular('Image')
            ->setEntityLabelInPlural('Images')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        // Product associated
        yield AssociationField::new('product', 'Produit associé')->setHelp('Sélectionner le produit pour lequel vous voulez ajouter une image');

        // Image file upload
        yield Field::new('imageFile', 'Image')->setFormType(VichImageType::class)->onlyOnForms();

        // Position of the image
        yield IntegerField::new('position', 'Position d\'affichage')->setSortable(true)->hideOnIndex();

        // Url image preview
        yield ImageField::new('url', 'Aperçu')->setBasePath('/uploads/products')->hideOnForm();

        // Alt text
        yield TextField::new('altHtml', 'Alt HTML')->setHelp('Le texte alt de l\'image');
    }
}
