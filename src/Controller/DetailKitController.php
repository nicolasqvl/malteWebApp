<?php

namespace App\Controller;

use App\Entity\Kit;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class DetailKitController extends AbstractController
{
    #[Route('/detail/kit/{idKit}', name: 'app_detail_kit', requirements: ['idKit' => '\d+'])]
    public function index($idKit, ManagerRegistry $doctrine, SessionInterface $session): Response
    {
        $session->set('kitId', $idKit);

        $kit = $doctrine->getRepository(Kit::class)->findOneBy(['id' => $idKit]);

        if(!$kit){
            throw $this->createNotFoundException("Aucun lot est associé avec ce Qr-Code!");
        }
        
        return $this->render('detail_kit/detailKit.html.twig', [
            'kit' => $kit,
        ]);
    }
}
