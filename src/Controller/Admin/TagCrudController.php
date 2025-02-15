<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TagCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tag::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['name' => 'ASC'])
            ->setPageTitle('index', 'Liste des %entity_label_plural%')
            ->setPageTitle('new', 'Créer un nouveau tag')
            ->setPageTitle('detail', fn (Tag $tag) => sprintf('Le tag <b>%s</b>', $tag->getName()))
            ->setPageTitle('edit', fn (Tag $tag) => sprintf('Modifier le tag <b>%s</b>', $tag->getName()))
            ->setEntityLabelInSingular('Tag')
            ->setEntityLabelInPlural('Tags')
        ;
    }
    
    public function configureFields(string $pageName): iterable
    {
        // Id
        yield IdField::new('id')->hideOnForm()->hideOnIndex();  

        // Name
        yield TextField::new('name', 'Nom du tag');

        // Slug
        yield SlugField::new('slug')->setTargetFieldName('name')->hideOnIndex();
    }
}
