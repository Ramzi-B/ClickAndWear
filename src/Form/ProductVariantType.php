<?php

namespace App\Form;

use App\Entity\Size;
use App\Entity\Color;
use App\Entity\Material;
use App\Entity\ProductVariant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ProductVariantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isActive', CheckboxType::class, [
                'label' => 'Disponible',
                'required' => false,
            ])
            // ->add('price', MoneyType::class, [
            //     'label' => 'Prix',
            //     'currency' => 'EUR'
            // ])
            ->add('price', NumberType::class, [
                'label' => 'Prix',
                'scale' => 2
            ])
            ->add('stock', IntegerType::class, [
                'label' => 'Stock'
            ])
            ->add('sku', TextType::class, [
                'label' => 'SKU',
                'disabled' => true,
                'help' => 'Généré automatiquement (ex: TSHIRT-ADIDAS-ROUGE-XL-HOMME)'
            ])
            ->add('size', EntityType::class, [
                'label' => 'Taille',
                'class' => Size::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez une taille',
            ])
            ->add('colors', EntityType::class, [
                'label' => 'Couleurs',
                'class' => Color::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
            ])
            ->add('materials', EntityType::class, [
                'label' => 'Matières',
                'class' => Material::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductVariant::class,
        ]);
    }
}
