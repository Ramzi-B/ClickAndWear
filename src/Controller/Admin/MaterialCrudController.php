<?php

namespace App\Controller\Admin;

use App\Entity\Material;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MaterialCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Material::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['name' => 'ASC'])
            ->setPageTitle('index', 'Liste des %entity_label_plural%')
            ->setPageTitle('new', 'Créer une nouvelle matière')
            ->setPageTitle('detail', fn (Material $material) => sprintf(
                'La matière <b>%s</b>', $material->getName()
            ))
            ->setPageTitle('edit', fn (Material $material) => sprintf(
                'Modifier la matière <b>%s</b>', $material->getName()
            ))
            ->setEntityLabelInSingular('Matière')
            ->setEntityLabelInPlural('Matières')
        ;
    }

    
    public function configureFields(string $pageName): iterable
    {
        // Id
        yield IdField::new('id')->hideOnForm()->hideOnIndex();
        
        // Name
        yield TextField::new('name', 'Nom de la matière');   
    }
}
