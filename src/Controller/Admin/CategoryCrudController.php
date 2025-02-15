<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['parent' => 'ASC', 'name' => 'ASC'])
            ->setPageTitle('index', 'Liste des %entity_label_plural%')
            ->setPageTitle('new', 'Créer une nouvelle catégorie')
            ->setPageTitle('detail', fn (Category $category) => sprintf('La catégorie <b>%s</b>', $category->getName()))
            ->setPageTitle('edit', fn (Category $category) => sprintf('Modifier la catégorie <b>%s</b>', $category->getName()))
            ->setEntityLabelInSingular('categorie')
            ->setEntityLabelInPlural('categories')
        ;
    }
    
    public function configureFields(string $pageName): iterable
    {
        // Name
        yield TextField::new('name', 'Nom de la catégorie')->setColumns(6);

        // Slug
        yield SlugField::new('slug', 'Slug de la catégorie (URL)')->setTargetFieldName('name')->setColumns(6)
            ->hideOnIndex()
        ;

        // Parent category
        yield AssociationField::new('parent', 'Catégorie parente')->setColumns(6)
            ->setCrudController(self::class)
            ->setRequired(false)
            ->autocomplete()
        ;

        // Sub categories
        yield AssociationField::new('subCategories', 'Sous Catégories')->setColumns(6)
            ->setCrudController(self::class)
            ->formatValue(function($value, $entity) {
                if ($entity->getSubCategories()) {
                    // Retrieve category names and concatenate them
                    return implode(', ', $entity->getSubCategories()->map(fn($subCategory) => $subCategory->getName())->toArray());
                }
                return '';
            })
            ->setFormTypeOptions([
                'by_reference' => false,
                'multiple' => true,
            ])
        ;

        // Groups
        yield AssociationField::new('groups', 'Groupes associés (facultatif)')
            ->setCrudController(self::class)
            ->formatValue(function($value, $entity) {
                if ($entity->getGroups()) {
                    // Retrieve group names and concatenate them
                    return implode(', ', $entity->getGroups()->map(fn($group) => $group->getName())->toArray());
                }
                return '';
            })
            ->setFormTypeOptions([
                'by_reference' => false,
                'multiple' => true, 
            ])->autocomplete()
        ;
    }


    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add('parent'); // Filter the parent category
    }
}
