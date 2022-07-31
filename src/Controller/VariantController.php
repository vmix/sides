<?php

namespace App\Controller;

use App\Entity\Variant;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class VariantController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository $productRepository,
    ) {
    }

    #[Route('/variants', name: 'add_variant', methods: 'POST')]
    public function add(Request $request, EntityManagerInterface $entityManager)
    {
        $variant = new Variant();
        $product = $this->productRepository->find($request->request->get('productId'));

        $variant->setVariantName($request->request->get('variantName'));
        $variant->setProduct($product);
        $variant->setPrice($request->request->get('price'));

        $entityManager->persist($variant);
        $entityManager->flush();

        $result = [
            'code' => Response::HTTP_CREATED,
            'message' => 'ok, variant is created',
        ];

        return $this->json($result, Response::HTTP_CREATED);

    }
}
