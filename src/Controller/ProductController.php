<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="list_product")
     */
    public function list(ProductRepository $productRepository,Request $request)
    {

        $checkBleu = $request->get('color1');
        $checkRouge = $request->get('color2');
        $checkVert = $request->get('color3');

        $colors = [];
        if ($checkBleu)
        $colors[] = $checkBleu;
        if ($checkRouge)
        $colors[] = $checkRouge;
        if ($checkVert)
        $colors[] = $checkVert;

        if (empty($colors))
        $products = $productRepository->findAll();
        else
        $products = $productRepository->findByColors($colors);

        $productLatest = $productRepository->findOneLatest();

        return $this->render('product/index.html.twig', [
            "products" => $products,
            "checkBleu" => $checkBleu,
            "checkRouge" => $checkRouge,
            "checkVert" => $checkVert,
            "productLatest" => $productLatest[0]
        ]);
    }

    /**
     * @Route("/product/{slug}",name="see_product")
     */
    public function show($slug,ProductRepository $productRepository){

        $product = $productRepository->findOneBy(["slug" => $slug]);

        return $this->render('product/see.html.twig',[
            "product" => $product
        ]);
    }
}
