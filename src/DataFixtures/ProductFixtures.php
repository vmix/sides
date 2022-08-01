<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $productList = [
            'Pizza',
            'Pie',
            'Sushi',
            'Pasta',
        ];

        foreach ($productList as $item) {
            $product = new Product();
            $product->setProductName($item);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
