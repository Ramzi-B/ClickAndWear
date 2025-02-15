<?php

namespace App\Controller\Admin;

use App\Entity\Color;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ColorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Color::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste des %entity_label_plural%')
            ->setPageTitle('new', 'Ajouter une couleur')
            ->setPageTitle('detail', fn (Color $color) => sprintf('La couleur <b>%s</b>', $color->getName()))
            ->setPageTitle('edit', fn (Color $color) => sprintf('Modifier la couleur <b>%s</b>', $color->getName()))
            ->setEntityLabelInSingular('Couleur')
            ->setEntityLabelInPlural('Couleurs')
        ;
    }

    
    public function configureFields(string $pageName): iterable
    {
        // Id
        yield IdField::new('id')->hideOnForm()->hideOnIndex();

        // Name
        yield TextField::new('name', 'Nom de la couleur');

        // Hexcode color
        yield ColorField::new('hexcode', 'Code couleur HEX (#FF0000)');
    }
}
