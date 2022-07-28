<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ProductController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ProductRepository $productRepository
    ) {
    }


    #[Route('/products', name: 'app_product', methods: 'GET')]
    public function index(): Response
    {
        $products = $this->productRepository->findAll();

        return $this->json($products);
    }

    #[Route('/products', name: 'app_product', methods: 'POST')]
    public function add(Request $request)
    {
        $product = new Product();

        $product->setProductName($request->request->get('product_name'));

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $result = [
            'code' => Response::HTTP_CREATED,
            'message' => 'ok',
            'status' => true
        ];

        return $this->json($result, Response::HTTP_CREATED);
    }
}
