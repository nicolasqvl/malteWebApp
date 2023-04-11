<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Form\OrderType;
use App\Entity\OrderDetail;
use App\Repository\ProductRepository;
use App\Repository\KitProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(KitProductRepository $kitProductRepository, SessionInterface $session, ProductRepository $productRepository, ManagerRegistry $doctrine, Request $request): Response
    {
        // Collect products in session
        $cart = $session->get('cart', []);
        $dataCart = [];
        $kitId = $session->get('kitId');

        foreach ($cart as $array) {
            $dataCart[] = $array;
        }

        $em = $doctrine->getManager();

        // Form DeclarationProductUsed
        $declaration = new Order;
        $formDeclaration = $this->createForm(OrderType::class, $declaration, ['kitId' => $kitId, 'user' => $this->getUser()->getUnit()]);
        $formDeclaration->handleRequest($request);

        $declaration->setOrderNumber(substr(md5(mt_rand(0, 5000)), 0, 5));
        $declaration->setState(false);

        if ($request->request->get('addDbb')) {

            if ($formDeclaration->isSubmitted() && $formDeclaration->isValid() && $dataCart != []) {

                $em->persist($declaration);

                // Fetch dataCart for set DetailProductUsed
                foreach ($dataCart as $ligne) {
                    $ligne['product'] = $productRepository->find($ligne['product']);
                    $ligneDetail = new OrderDetail;
                    $ligneDetail->setQuantity($ligne['quantity']);
                    $ligneDetail->setProduct($ligne['product']);
                    $ligneDetail->setOrderProduct($declaration);

                    $em->persist($ligneDetail);

                    $productInCart = $ligneDetail->getProduct()->getId();
                    $productInCartName = $ligneDetail->getProduct()->getName();
                    $quantityInCart = $ligneDetail->getQuantity();

                    // Fetch container ID from cart
                    $containerChoised = $declaration->getKit()->getId();
                    // Fetch containerProduct with same container ID from cart
                    $containerProduct = $kitProductRepository->findSameProductOfCart($containerChoised, $productInCart);

                    if($containerProduct === []){
                        $this->addFlash('invalided_cart', 'Le produit : '.$productInCartName.' n\' existe pas dans le lot ! Vérifiez si vous avez choisi le bon lot. Si oui, contactez votre responsable logistique.');
                        return $this->redirectToRoute('app_cart');
                    }

                    foreach($containerProduct as $productInContainer){
                        $productQuantity = $productInContainer->getProductQuantity();
                        $productId = $productInContainer->getProduct()->getId();
                        try
                        {
                            if($productInCart == $productId && $productQuantity >= $quantityInCart){
                                $productInContainer->setProductQuantity($productQuantity - $quantityInCart);
                            }else{
                                throw new Exception ('Produit '.$productInCartName.' se trouve en trop faible quantité dans les stocks ('.$productQuantity.') ');
                            }
                        }
                        catch(Exception $e)
                        {
                            $this->addFlash('invalided_cart', $e->getMessage().' Votre panier n\'a donc pas été validé ! Vérifiez si vous avez choisi le bon lot. Si oui, contactez votre responsable logistique.');
                            return $this->redirectToRoute('app_cart');
                        }
                    
                    }
                }

                $em->flush();
                $dataCart = [];
                $session->clear();
                $this->addFlash('valided_cart', 'Votre panier a été validé !');
                return $this->redirectToRoute('app_product');

            } elseif (isset($dataCart)) {
                // If cart is empty
                $this->addFlash('emptyCrat', 'Vous ne pouvez pas envoyer un panier vide');
            }
        }

        return $this->render('cart/cart.html.twig', [
            'dataCart' => $dataCart,
            'formDeclaration' => $formDeclaration->createView(),
        ]);
    }

    #[Route('/addProduct/{id}', name: 'app_add_product')]
    public function addProduct(Product $product, SessionInterface $session)
    {

        $cart = $session->get('cart');
        $id = $product->getId();

        // ----- Add one product in quantity -----
        if (!empty($cart)) {
            foreach ($cart as $key => $infoProduct) {
                if ($infoProduct['product'] == $id) {
                    $cart[$key]['quantity']++;
                }
            }
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/deleteProduct/{id}', name: 'app_delete_product')]
    public function removeProduct(Product $product, SessionInterface $session)
    {

        // ----- Delete one product in quantity -----
        $cart = $session->get('cart');
        $id = $product->getId();

        if (!empty($cart)) {
            foreach ($cart as $key => $infoProduct) {
                if ($infoProduct['product'] == $id && $infoProduct['quantity'] > 1) {
                    $cart[$key]['quantity']--;
                } elseif ($infoProduct['product'] == $id && $infoProduct['quantity'] == 1) {
                    unset($cart[$key]);
                }
            }
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/delateAll/{id}', name: 'app_delete_all')]
    public function removeAll(Product $product, SessionInterface $session)
    {

        // ----- Delete all products in line -----
        $cart = $session->get('cart');
        $id = $product->getId();

        if (!empty($cart)) {
            foreach ($cart as $key => $infoProduct) {
                if ($infoProduct['product'] == $id) {
                    unset($cart[$key]);
                }
            }
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }
}
