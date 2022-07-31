<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ProductController extends AbstractController
{
    #[Route('/products', name: 'products', methods: 'GET')]
    public function index(ProductRepository $productRepository): JsonResponse
    {
//        $products = $this->productRepository->findAll();
        $products = $productRepository->getProducts();

        $result = [
            'code' => 200,
            'message' => 'ok, list',
            'products' => $products
        ];

        return $this->json($result, Response::HTTP_OK);
    }

    #[Route('/products', name: 'add_product', methods: 'POST')]
    public function add(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $product = new Product();

        $product->setProductName($request->request->get('productName'));

        $entityManager->persist($product);
        $entityManager->flush();

        $result = [
            'code' => 201,
            'message' => 'ok, product is created',
        ];

        return $this->json($result, Response::HTTP_CREATED);
    }

    #[Route('/products/{id}', name: 'update_product', methods: 'PUT')]
    public function update(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository
    ): JsonResponse
    {
        try {
            $product = $productRepository->find($id);

            if (!$product){
                $data = [
                    'code' => 404,
                    'message' => "product not found",
                ];
                return $this->json($data, Response::HTTP_NOT_FOUND);
            }
            $request = $this->transformJsonBody($request);

            $product->setProductName($request->request->get('productName'));
            $entityManager->flush();

            $data = [
                'code' => 200,
                'message' => "product updated successfully",
            ];

            return $this->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            $data = [
                'code' => 422,
                'message' => "Data no valid",
            ];

            return $this->json($data, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

    }

    #[Route('/products/{id}', name: 'delete_product', methods: 'DELETE')]
    public function delete($id, ProductRepository $productRepository): JsonResponse
    {
        $product = $productRepository->find($id);

        if (!$product){
            $data = [
                'code' => 404,
                'message' => "product not found",
            ];

            return $this->json($data, Response::HTTP_NOT_FOUND);
        }

        $productRepository->remove($product, true);

        return $this->json([
            'code' => 200,
            'message' => 'ok, product is deleted'
        ], Response::HTTP_OK);
    }

    protected function transformJsonBody(Request $request): Request
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }
}
