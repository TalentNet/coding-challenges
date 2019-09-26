<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;


/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    private function serialize($value) {
        $encoders = array(new JsonEncoder());
        $normalizer = new GetSetMethodNormalizer();
        $normalizer->setCircularReferenceLimit(0);
        // Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers, $encoders);
        return $serializer->serialize($value, 'json');
    }

    /**
     * @Route("s/", name="product_index", methods="GET")
     */
    public function getAll(ProductRepository $productRepository): Response
    {
        $jsonContent = $this->serialize($productRepository->findAll());
        return JsonResponse::fromJsonString($jsonContent);
    }

    /**
     * @Route("/", name="product_new", methods="POST")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function add(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $product = new Product();
        $body = $request->getContent();
        $data = json_decode($body, true);

        //$form = $this->createForm(ProductType::class, $product);
        //$form->submit($data);

        //$validator = $this->get("validator");
        //$errors = $validator->validate($product);

        /*if(count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse($errorsString);
        }*/

        $product->setName($data['name']);
        $product->setSku($data['sku']);
        $product->setQuantity($data['quantity']);
        $product->setPrice($data['price']);
        

        $categoryRepository = $em->getRepository(Category::class);
        $category = $categoryRepository->findOneBy(['name' => $data['category'] ]);
        if (!$category) {
            $category = new Category();
            $category->setName($data['category']);
            $em->persist($category);
        }
        $product->setCategory($category);

        $em->persist($product);
        $em->flush();

        $jsonContent = $this->serialize($product);
        return JsonResponse::fromJsonString($jsonContent);
    }

    /**
     * @Route("/{id}", name="product_show", methods="GET")
     */
    public function getOne(Product $product): Response
    {
        $jsonContent = $this->serialize($product);
        return JsonResponse::fromJsonString($jsonContent);
    }

    /**
     * @Route("/{id}", name="product_edit", methods="PUT")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function update(Request $request, Product $product): Response
    {
        $em = $this->getDoctrine()->getManager();

        $body = $request->getContent();
        $data = json_decode($body, true);

        //$form = $this->createForm(ProductType::class, $product);
        //$form->submit($data);

        //$validator = $this->get("validator");
        //$errors = $validator->validate($product);

        /*if(count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse($errorsString);
        }*/

        $product->setName($data['name']);
        $product->setSku($data['sku']);
        $product->setQuantity($data['quantity']);
        $product->setPrice($data['price']);

        $categoryRepository = $em->getRepository(Category::class);
        $category = $categoryRepository->findOneBy(['name' => $data['category'] ]);
        if (!$category) {
            $category = new Category();
            $category->setName($data['category']);
            $em->persist($category);
        }
        $product->setCategory($category);

        $em->flush();

        $jsonContent = $this->serialize($product);
        return JsonResponse::fromJsonString($jsonContent);
    }

    /**
     * @Route("/{id}", name="product_delete", methods="DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function delete(Request $request, Product $product): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('product_index');
    }
}
