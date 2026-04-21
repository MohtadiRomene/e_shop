<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Accessoire;
use App\Entity\Chaussure;
use App\Entity\Produit;
use App\Entity\Vetement;
use PHPUnit\Framework\TestCase;

final class ProduitTest extends TestCase
{
    public function testGetTypeReturnsExpectedValuePerSubclass(): void
    {
        self::assertSame('produit', (new Produit())->getType());
        self::assertSame('vetement', (new Vetement())->getType());
        self::assertSame('chaussure', (new Chaussure())->getType());
        self::assertSame('accessoire', (new Accessoire())->getType());
    }

    public function testStockAndEnStockBehavior(): void
    {
        $produit = (new Produit())
            ->setNomProduit('Test')
            ->setPrix(100.0)
            ->setEnStock(true)
            ->setStock(3);

        self::assertTrue($produit->isEnStock());

        $produit->setStock(0);

        self::assertFalse($produit->isEnStock());
    }
}
