<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }

    // Display navbar depending on the connection status
    public function menu(){
        $listMenu = [
            ['title'=> "Homepage", "text"=>'ACCUEIL', "url"=> $this->generateUrl('homepage')],
            ['title'=> "SignIn", "text"=>'CONNEXION', "url"=> $this->generateUrl('app_login'), "user"=>false],
            ['title'=> "Product", "text"=>'PRODUITS', "url"=> $this->generateUrl('app_product'), "user"=>true],
            ['title'=> "Cart", "text"=>'PANIER', "url"=> $this->generateUrl('app_cart'), "user"=>true],
            ['title'=> "SignOut", "text"=>'DÉCONNEXION', "url"=> $this->generateUrl('app_logout'), "user"=>true],
        ];

        return $this->render("parts/menu.html.twig", ["listMenu" => $listMenu]);
    }
}
