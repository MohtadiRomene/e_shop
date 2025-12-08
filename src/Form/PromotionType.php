<?php

namespace App\Form;

use App\Entity\Promotion;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reduction', NumberType::class, [
                'label' => 'Réduction (%)',
                'attr' => ['class' => 'form-control rounded-0', 'min' => '0', 'max' => '100', 'step' => '0.01'],
            ])
            ->add('dateExpiration', DateType::class, [
                'label' => 'Date d\'expiration',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control rounded-0'],
            ])
            ->add('produits', EntityType::class, [
                'label' => 'Produits concernés',
                'class' => Produit::class,
                'query_builder' => function (ProduitRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.nomProduit', 'ASC');
                },
                'choice_label' => 'nomProduit',
                'multiple' => true,
                'expanded' => false,
                'attr' => ['class' => 'form-control rounded-0'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Promotion::class,
        ]);
    }
}

