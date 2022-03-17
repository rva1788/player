<?php

namespace App\Controller;

use App\ApiData\ApiRequest;
use App\ApiData\ApiResponse;
use App\Entity\Player;
use App\Meta\Meta;
use App\Repository\PlayerRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlayerController extends AbstractController implements JsonRequestInterface
{
    private ApiResponse $response;

    public function __construct()
    {
        $this->response = new ApiResponse();
    }

    #[Route('/player', name: 'player_add', methods: ['POST'])]
    public function add(EntityManagerInterface $em, Request $request): Response
    {
        /** @var ApiRequest $data */
        $data = $request->attributes->get('json_request');

        try {
            $player = (new Player())
                ->setName($data->get('name'))
                ->setCountry($data->get('country'))
                ->setBirthDate(new DateTime($data->get('birth_date')))
                ->setPosition($data->get('position'));
            $em->persist($player);
            $em->flush();

            $this->response->setData($player->toArray());
        } catch (Exception $exception) {
            $this->response->setError($exception);
        }

        return $this->response->toJsonResponse();
    }

    #[Route('/player/{id}', name: 'player_edit', methods: ['PUT'])]
    public function edit(EntityManagerInterface $em, PlayerRepository $playerRepo, Request $request, int $id): JsonResponse
    {
        /** @var ApiRequest $data */
        $data = $request->attributes->get('json_request');

        $em->beginTransaction();
        try {
            $player = $playerRepo->findOneBy(['id' => $id]);
            if (empty($player)) {
                throw new Exception("Player not found");
            }

            if ($data->has('name')) {
                $player->setName($data->get('name'));
            }
            if ($data->has('country')) {
                $player->setCountry($data->get('country'));
            }
            if ($data->has('position')) {
                $player->setCountry($data->get('position'));
            }
            if ($data->has('birth_date')) {
                $player->setBirthDate(new DateTime($data->get('birth_date')));
            }

            $em->flush();
            $em->commit();
        } catch (Exception $exception) {
            $em->rollback();
            $this->response->setError($exception);
        }

        return $this->response->toJsonResponse();
    }

    #[Route('/player/{id}', name: 'player_delete', methods: ['DELETE'])]
    public function remove(EntityManagerInterface $em, PlayerRepository $playerRepo, int $id): JsonResponse
    {
        try {
            $player = $playerRepo->findOneBy(['id' => $id]);
            if (empty($player)) {
                throw new Exception("Player not found");
            }

            $em->remove($player);
            $em->flush();
        } catch (Exception $exception) {
            $this->response->setError($exception);
        }

        return $this->response->toJsonResponse();
    }

    /**
     * @param PlayerRepository $playerRepo
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('/player/{id}', name: 'player_get_one', methods: ['GET'])]
    public function get_one(PlayerRepository $playerRepo, int $id): JsonResponse
    {
        try {
            $player = $playerRepo->findOneBy(['id' => $id]);
            if (empty($player)) {
                throw new Exception("Player not found");
            }

            $this->response->setData($player->toArray());
        } catch (Exception $exception) {
            $this->response->setError($exception);
        }

        return $this->response->toJsonResponse();
    }

    #[Route('/player', name: 'player_get', methods: ['GET'])]
    public function get(PlayerRepository $playerRepo, Request $request): JsonResponse
    {
        /** @var ApiRequest $data */
        $data = $request->attributes->get('json_request');

        $meta = (new Meta())
            ->setUrl('http://localhost:8181')
            ->setPerPage(PlayerRepository::LIMIT)
            ->setTotal($playerRepo->getCount())
            ->setFilter($data);

        $players = $playerRepo->findByFilter($data);
        /** @var Player $player */
        foreach ($players as $player) {
            $array[] = $player->toArray();
        }

        return $this->response
            ->setMeta($meta)
            ->setData($array ?? [])
            ->toJsonResponse();
    }
}
