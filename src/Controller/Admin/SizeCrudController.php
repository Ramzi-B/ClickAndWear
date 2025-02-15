<?php

namespace App\Controller\Admin;

use App\Entity\Size;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SizeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Size::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste des %entity_label_plural%')
            ->setPageTitle('new', 'Créer une nouvelle Taille')
            ->setPageTitle('detail', fn (Size $size) => sprintf('La taille <b>%s</b>', $size->getName()))
            ->setPageTitle('edit', fn (Size $size ) => sprintf('Modifier la taille <b>%s</b>', $size->getName()))
            ->setEntityLabelInSingular('Taille')
            ->setEntityLabelInPlural('Tailles')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        // Id
        yield IdField::new('id')->hideOnForm()->hideOnIndex();

        // Name
        yield TextField::new('name', 'Nom de la taille');
    }
}
