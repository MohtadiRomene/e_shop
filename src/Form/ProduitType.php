<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomProduit', TextType::class, [
                'label' => 'Nom du produit',
                'attr' => [
                    'class' => 'form-control rounded-0',
                    'placeholder' => 'Ex: T-shirt en coton',
                ],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank([
                        'message' => 'Le nom du produit est obligatoire',
                    ]),
                    new \Symfony\Component\Validator\Constraints\Length([
                        'min' => 3,
                        'max' => 255,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix (€)',
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control rounded-0',
                    'step' => '0.01',
                    'min' => '0.01',
                    'placeholder' => '0.00',
                ],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank([
                        'message' => 'Le prix est obligatoire',
                    ]),
                    new \Symfony\Component\Validator\Constraints\Positive([
                        'message' => 'Le prix doit être positif',
                    ]),
                ],
            ])
            ->add('description', \Symfony\Component\Form\Extension\Core\Type\TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'class' => 'form-control rounded-0',
                    'rows' => 4,
                    'placeholder' => 'Description détaillée du produit...',
                ],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\Length([
                        'max' => 1000,
                        'maxMessage' => 'La description ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('stock', NumberType::class, [
                'label' => 'Stock disponible',
                'attr' => [
                    'class' => 'form-control rounded-0',
                    'min' => '0',
                    'placeholder' => '0',
                ],
                'data' => 0,
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank([
                        'message' => 'Le stock est obligatoire',
                    ]),
                    new \Symfony\Component\Validator\Constraints\PositiveOrZero([
                        'message' => 'Le stock doit être positif ou zéro',
                    ]),
                ],
            ])
            ->add('enStock', \Symfony\Component\Form\Extension\Core\Type\CheckboxType::class, [
                'label' => 'En stock',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
                'label_attr' => ['class' => 'form-check-label'],
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image du produit',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '5M',
                        'maxSizeMessage' => 'L\'image ne doit pas dépasser 5 Mo',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => 'Le fichier doit être une image (JPEG, PNG ou WebP)',
                    ]),
                ],
                'attr' => ['class' => 'form-control rounded-0'],
                'help' => 'Formats acceptés: JPEG, PNG, WebP. Taille max: 5 Mo',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}

