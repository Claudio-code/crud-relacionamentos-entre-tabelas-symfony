<?php

namespace App\Controller;

use App\Entity\VideoGame;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/videoGame", name="video_game")
 */
class VideoGameController extends AbstractController
{
    

    /**
     * @Route("/create", name="_create", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        // pegando os dados que foram mandados na requisição
        $data = $request->request->all();

        // mandando dados para entidade
        $videoGame = new VideoGame();
        $videoGame->setName($data['name']);
        $videoGame
            ->setCreatedAt(new \DateTime("now", new \DateTimeZone("America/Sao_Paulo")))
            ->setUpdatedAt(new \DateTime("now", new \DateTimeZone("America/Sao_Paulo")))
        ;

        // chamando orm para persistir os dados na tabela
        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->persist($videoGame);
        $doctrine->flush();

        // devolvendo os dados salvos na tabela
        return $this->json([
            'video game salvo' => $videoGame
        ]);
    }
}
