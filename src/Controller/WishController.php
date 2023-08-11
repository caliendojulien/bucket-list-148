<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/wishes', name: 'wish')]
class WishController extends AbstractController
{
    #[Route('/', name: '_list')]
    public function list(
        WishRepository $wishRepository
    ): Response
    {
        $wishes = $wishRepository->findBy(
            ["isPublished" => true],
            ["date" => "ASC"]
        );
        return $this->render(
            'wish/list.html.twig',
            compact('wishes')
        );
    }

    #[Route('/detail/{wish}', name: '_details', requirements: ["wish" => "\d+"])]
    public function detail(Wish $wish): Response
    {
        return $this->render(
            'wish/detail.html.twig',
            compact('wish')
        );
    }

    #[Route('/new', name: '_new')]
    public function nouveau(
        EntityManagerInterface $entityManager,
        Request $requete
    ): Response
    {
        $wish = new Wish();
        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($requete);

        $wish->setIsPublished(true);
        $wish->setDate(new \DateTime());
        $wish->setAuteur("Caliendo Julien");

        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            $entityManager->persist($wish);
            $entityManager->flush();
            return $this->redirectToRoute('wish_details', ['wish' => $wish->getId()]);
        }
        return $this->render(
            'wish/nouveau.html.twig',
            compact('wishForm')
        );
    }





}
