<?php

namespace App\Command;

use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Commande pour initialiser le stock de tous les produits
 */
#[AsCommand(
    name: 'app:init-stock',
    description: 'Initialise le stock pour tous les produits (par défaut 50 unités)'
)]
class InitStockCommand extends Command
{
    public function __construct(
        private ProduitRepository $produitRepository,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('stock', 's', InputOption::VALUE_OPTIONAL, 'Nombre d\'unités en stock par défaut', 50)
            ->setHelp('Cette commande initialise le stock de tous les produits qui n\'ont pas de stock ou qui sont en rupture.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $stockValue = (int) $input->getOption('stock');

        $produits = $this->produitRepository->findAll();
        $updated = 0;

        foreach ($produits as $produit) {
            if ($produit->getStock() <= 0 || !$produit->isEnStock()) {
                $produit->setStock($stockValue);
                $produit->setEnStock(true);
                $updated++;
            }
        }

        $this->entityManager->flush();

        $io->success(sprintf(
            'Stock initialisé pour %d produit(s) avec %d unités chacun.',
            $updated,
            $stockValue
        ));

        return Command::SUCCESS;
    }
}
