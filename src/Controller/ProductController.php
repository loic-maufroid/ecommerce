<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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

    /**
     * @Route("/admin/product/new",name="create_product")
     */
    public function new(Request $request,SluggerInterface $slugger){

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $product = new Product();

        $form = $this->createForm(ProductType::class,$product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product->setSlug($slugger->slug($product->getName())->lower());

            $image = $form->get('image')->getData();

            if ($image !== null) {
                // Je récupère le dossier où j'upload mes images
                $uploadDir = __DIR__.'/../../public/img/';
                // Je fais l'upload en générant un nom pour l'image comme aerf1234.jpg
                $fileName = uniqid().'.'.$image->guessExtension();
                $image->move($uploadDir, $fileName);

                // Je mets à jour l'entité pour la BDD
                $product->setImage($fileName);
            }
            // On peut aussi utiliser l'autowiring :
            // create(EntityManagerInterface $entityManager)
            $entityManager = $this->getDoctrine()->getManager();

            // On demande à Doctrine de mettre l'objet en attente
            $entityManager->persist($product);

            // Exécute la(es) requête(s) (INSERT...)
            $entityManager->flush();
            
            $product = new Product();

            $form = $this->createForm(ProductType::class,$product);

            $this->addFlash('success','Produit bien ajouté !');
            // return $this->redirectToRoute('product_list');
        }

        return $this->render('product/create.html.twig', [
        "form" => $form->createView()
            ]);

    }
}
