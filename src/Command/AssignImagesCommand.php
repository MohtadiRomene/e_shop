<?php

namespace App\Command;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:assign-images',
    description: 'Assign images to existing products',
)]
class AssignImagesCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $produitRepository = $this->entityManager->getRepository(Produit::class);
        $produits = $produitRepository->findAll();

        if (empty($produits)) {
            $io->warning('Aucun produit trouvé dans la base de données.');
            return Command::FAILURE;
        }

        // Liste des images disponibles
        $availableImages = [
            'product-item-1.jpg',
            'product-item-2.jpg',
            'product-item-3.jpg',
            'product-item-4.jpg',
            'product-item-5.jpg',
            'product-item-6.jpg',
            'product-item-7.jpg',
            'product-item-8.jpg',
            'product-item-9.jpg',
            'product-item-10.jpg',
            'wishlist-item1.jpg',
            'wishlist-item2.jpg',
            'wishlist-item3.jpg',
            'cat-item1.jpg',
            'cat-item2.jpg',
            'cat-item3.jpg',
        ];

        $io->progressStart(count($produits));
        $updated = 0;

        foreach ($produits as $index => $produit) {
            // Si le produit n'a pas déjà d'image
            if (!$produit->getImage()) {
                // Assigner une image de manière cyclique
                $imageIndex = $index % count($availableImages);
                $produit->setImage($availableImages[$imageIndex]);
                $updated++;
            }
            $io->progressAdvance();
        }

        $this->entityManager->flush();
        $io->progressFinish();

        $io->success(sprintf(
            'Images assignées avec succès ! %d produit(s) mis à jour sur %d.',
            $updated,
            count($produits)
        ));

        return Command::SUCCESS;
    }
}
