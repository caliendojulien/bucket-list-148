<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\UserRepository;
use App\Repository\WishRepository;
use App\Services\Censurator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/wishes', name: 'wish')]
/**
 * @Route("/wishes", name="wish")
 */
class WishController extends AbstractController
{
    #[Route('/', name: '_list')]
    public function list(
        WishRepository      $wishRepository,
        HttpClientInterface $client
    ): Response
    {

        $wishes = $wishRepository->findAllPublished();
        $reponse = $client->request(
            'GET',
            'https://chuckn.neant.be/api/rand'
        );
        $blagues = $reponse->toArray();
        return $this->render(
            'wish/list.html.twig',
            compact('wishes', 'blagues')
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
    #[IsGranted('ROLE_USER')]
    public function nouveau(
        EntityManagerInterface $entityManager,
        Request                $requete,
        UserRepository         $userRepository,
        Censurator             $censurator
    ): Response
    {
        $wish = new Wish();
        $wish->setIsPublished(true);
        $wish->setDate(new \DateTime());
        $wish->setTitle("Un titre");

        $wish->setAuthor($this->getUser());

        $wishForm = $this->createForm(WishType::class, $wish);

        $wishForm->handleRequest($requete);

        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            $wish->setTitle($censurator->purify($wish->getTitle()));
            $wish->setRealise(true);
            $wish->setDescription($censurator->purify($wish->getDescription()));
            $entityManager->persist($wish);
            $entityManager->flush();
            return $this->redirectToRoute('wish_details', ['wish' => $wish->getId()]);
        }
        return $this->render(
            'wish/nouveau.html.twig',
            compact('wishForm')
        );
    }

    #[Route('/api/wishes', name: '_api_wishes')]
    function api(
        WishRepository      $wishRepository,
        SerializerInterface $serializer
    ): Response
    {
        $wishes = $wishRepository->findAll();
        return new JsonResponse(
            $serializer->serialize($wishes, 'json',
                ['groups' => 'wishes:read']
            ),
            200,
            [],
            true
        );
    }


}
