<?php

namespace App\Controller;

use Cassandra\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_home')]
    public function home(): Response
    {
        return $this->render('main/home.html.twig');
    }

    #[Route('/about-us', name: 'main_about_us')]
    public function about_us(): Response
    {
        // Lire le fichier json
         $fichier = file_get_contents('./json/team.json');
        // Decoder le fichier json
         $jsonAssociatif = json_decode(file_get_contents('./json/team.json'), true);
         $jsonPasAssociatif = json_decode(file_get_contents('./json/team.json'), false);
         dump($jsonAssociatif);
         dump($jsonPasAssociatif);
        // Envoyer le json au twig
        return $this->render(
            'main/about_us.html.twig',
            [
                'personnes' => json_decode(file_get_contents('./json/team.json'), true)
            ]
        );
    }
}
