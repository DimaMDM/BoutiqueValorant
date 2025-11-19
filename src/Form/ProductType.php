<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\ProductStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit',
                'attr' => [
                    'class' => 'w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-red-600',
                    'placeholder' => 'Ex: Reaver Vandal'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom du produit est obligatoire',
                    ]),
                ],
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix (VP)',
                'attr' => [
                    'class' => 'w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-red-600',
                    'placeholder' => 'Ex: 2175'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le prix est obligatoire',
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'class' => 'w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-red-600',
                    'placeholder' => 'Description du produit...',
                    'rows' => 4
                ],
            ])
            ->add('stock', IntegerType::class, [
                'label' => 'Stock',
                'attr' => [
                    'class' => 'w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-red-600',
                    'placeholder' => 'Ex: 100'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le stock est obligatoire',
                    ]),
                ],
            ])
            ->add('status', EnumType::class, [
                'class' => ProductStatus::class,
                'label' => 'Statut',
                 'choice_label' => fn ($choice) => match ($choice) {
                    ProductStatus::AVAILABLE => 'Disponible',
                    ProductStatus::OUT_OF_STOCK => 'En rupture',
                    ProductStatus::PRE_ORDER => 'En précommande',
                },
                'attr' => [
                    'class' => 'w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-red-600',
                ],
                 'constraints' => [
                    new NotBlank([
                        'message' => 'Le statut est obligatoire',
                    ]),
                ],
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image du produit',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-red-600',
                    'accept' => 'image/*'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/gif',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (JPG, PNG, GIF, WEBP)',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
