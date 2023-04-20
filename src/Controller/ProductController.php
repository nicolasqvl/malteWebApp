<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\SearchProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(CategoryRepository $categoryRepository, ProductRepository $productRepository, Request $request, SessionInterface $session): Response
    {

        $filters = $request->get("categories");

        $products = $productRepository->findAllAndFiltered($filters);

        $categories = $categoryRepository->findAll();

        if($request->get('ajax')){
            return new JsonResponse([
                'content' => $this->renderView('product/content.html.twig', [
                    'products' => $products
                ])]);
        }
        
        // ----- Search Bar in Product Page -----
        // $search = new Product();
        // $form = $this->createForm(SearchProductType::class, $search);
        // $form->handleRequest($request);

        // $products = $productRepository->findProductByName($search);

        // ----- Send selected product in session with choiced quantity -----
        if($request->request->get('add')){

            $cart = $session->get('cart');
            $add = false;

            if(!empty($cart)){
                foreach($cart as $key => $infoProduct){
                    if($request->request->get('productName') == $infoProduct['productName']){
                        $cart[$key]['quantity'] += $request->request->get('quantity');
                        $add = true;
                    }
                }
                if(!$add){
                    $cart[] = ['quantity'=>$request->request->get('quantity'), 'product'=>$request->request->get('product'), 'productName'=>$request->request->get('productName')];
                }
                $session->set('cart',$cart);
            }else{
                $session->set('cart',[ ['quantity' => $request->request->get('quantity'), 'product' => $request->request->get('product'), 'productName'=>$request->request->get('productName')]]);
            }
         
            // $session->clear();
            $this->addFlash('info', 'Vous avez ajouté '.$request->request->get('quantity').' '.$request->request->get('productName').' dans le ');

        }

        return $this->render('product/product.html.twig', [
            // 'search' => $search,
            'products' => $products,
            'categories' => $categories,
            // 'form' => $form->createView()
        ]);
    }
}
