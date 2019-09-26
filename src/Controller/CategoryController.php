<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
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
     * @Route("s/", name="category_index", methods="GET")
     */
    public function getAll(CategoryRepository $categoryRepository): Response
    {
        $jsonContent = $this->serialize($categoryRepository->findAll());
        return JsonResponse::fromJsonString($jsonContent);
    }

}
