<?php

namespace App\Controller;

use App\Entity\VideoGame;
use App\Repository\VideoGameRepository;
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
     * @Route("/", name="_get_all", methods={"GET"})
     */
    public function index(VideoGameRepository $gameRepository): JsonResponse
    {
        return $this->json($gameRepository->findAll());
    }

    /**
     * @Route("/delete/{id}", name="_delete", methods={"DELETE"})
     */
    public function delete(VideoGame $videoGame): JsonResponse
    {
        // pegando a entidade e chamando o doctrine
        // para remover os dados dela do banco
        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->remove($videoGame);
        $doctrine->flush();

        return $this->json([ "removed" => true ]);
    }

    /**
     * @Route("/update/{id}", name="_update", methods={"PUT"})
     */
    public function update(VideoGame $videoGame, Request $request): JsonResponse
    {
        // pegando os dados que foram mandados na requisição
        $data = $request->request->all();

        // inserindo o dado que será alterado
        $videoGame->setName($data['name']);

        // atualizando data da ultima atualização dessa entidade no banco
        $videoGame->setUpdatedAt(new \DateTime("now", new \DateTimeZone("America/Sao_Paulo")));

        // salvando as alterações no banco
        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->flush();

        // retornando entidade em um objeto json
        return $this->json($videoGame);
    }

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
