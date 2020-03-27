<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(ProductRepository $productRepository)
    {
        $productsRand = $productRepository->findThreeRandom();

        $productRandHeart = $productRepository->findOneRandomHeart();

        $productsLatest = $productRepository->findFourLatest();

        return $this->render('index/index.html.twig',[
            "productRand1" => $productsRand[0],
            "productRand2" => $productsRand[1],
            "productRand3" => $productsRand[2],
            "productRandHeart" => $productRandHeart[0],
            "productsLatest" => $productsLatest
        ]);
    }
}
