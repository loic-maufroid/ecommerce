<?php

namespace App\Form;

use App\Entity\Product;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',null,['label' => 'Nom'])
            ->add('description',TextareaType::class)
            ->add('price',MoneyType::class,['label' => 'Prix'])
            ->add('creation_date',DateType::class,['label' => "Date de création"])
            ->add('heart',null,['label' => "Coup de coeur"])
            ->add('colors', ChoiceType::class,[
                    'choices' => [
                        'Bleu' => "bleu",
                        'Rouge' => "rouge",
                        'Vert' => "vert"
                    ],
                    'expanded' => true,
                    'multiple' => true,
                    'label_attr' => [
                        'class' => 'checkbox-inline'
                    ],
                    'label' => "Couleurs"
                ]
            )
            ->add('image',FileType::class,[
                'required' => false,
                'label' => "Image (facultatif)"
            ])
            ->add('discount',IntegerType::class,[
                'required' => false,
                'label' => "Promotion (pourcentage, de 10 à 75, facultatif)"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class
        ]);
    }
}
