<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/wishes', name: 'wish')]
class WishController extends AbstractController
{
    #[Route('/', name: '_list')]
    public function list(): Response
    {
        return $this->render('wish/list.html.twig');
    }
    #[Route('/detail/{id}', name: '_details', requirements: ["id"=>"\d+"])]
    public function detail(int $id): Response
    {
        return $this->render('wish/detail.html.twig');
    }
}
