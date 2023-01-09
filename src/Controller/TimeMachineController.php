<?php

namespace App\Controller;

use App\Entity\TimeMachine;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TimeMachineController extends AbstractController
{
    #[Route('/time-machine', name: 'create_time_machine', methods: ['POST'])]
    public function createTimeMachine(ManagerRegistry $managerRegistry, Request $request): JsonResponse
    {

        $entityManager = $managerRegistry->getManager();
        $content = json_decode($request->getContent(), true);

        $timeMachine = new TimeMachine();
        $timeMachine->setName($content['name']);
        $timeMachine->setResourceUrl($content['resourceUrl']);


        $entityManager->persist($timeMachine);
        $entityManager->flush();

        return new JsonResponse([
            'id' => $timeMachine->getId(),
            'name' => $timeMachine->getName(),
            'resourceUrl' => $timeMachine->getResourceUrl(),
        ]);
    }

    #[Route('/time-machine/random', name: 'get_random_time_machine', methods: ['GET'])]
    public function getRandomTimeMachine(ManagerRegistry $managerRegistry): JsonResponse
    {
        $timeMachines = $managerRegistry->getRepository(TimeMachine::class)->findAll();

        $randomTimeMachine = $timeMachines[array_rand($timeMachines)];

        $tenantId = "common";
        $clientId = "85d0a6d1-02e7-45f6-87d9-971c9ab4a8c1";

        $guzzle = new \GuzzleHttp\Client();
        $url = 'https://login.microsoftonline.com/' . $tenantId . '/oauth2/v2.0/authorize';
        $params = array([
            'client_id' => $clientId,
            'response_type' => 'code',
            'redirect_uri' => 'http://localhost:8000/time-machine/random',
            'response_mode' => 'query',
            'scope' => 'https://graph.microsoft.com/.default',
            'state' => '12345',
        ]);

        // call the API and ignore certificate errors
        $response = $guzzle->request('GET', $url, $params);
        $response = json_decode($response->getBody(), true);



        return new JsonResponse([
            'id' => $randomTimeMachine->getId(),
            'name' => $randomTimeMachine->getName(),
            'resourceUrl' => $randomTimeMachine->getResourceUrl(),
        ]);
    }

    #[Route('/time-machine/{id}', name: 'get_time_machine', methods: ['GET'])]
    public function getTimeMachine(ManagerRegistry $managerRegistry, int $id): JsonResponse
    {
        $timeMachine = $managerRegistry->getRepository(TimeMachine::class)->find($id);

        return new JsonResponse([
            'id' => $timeMachine->getId(),
            'name' => $timeMachine->getName(),
            'resourceUrl' => $timeMachine->getResourceUrl(),
        ]);
    }

    #[Route('/time-machine/{id}', name: 'update_time_machine', methods: ['PUT'])]
    public function updateTimeMachine(ManagerRegistry $managerRegistry, Request $request, int $id): JsonResponse
    {
        $entityManager = $managerRegistry->getManager();
        $timeMachine = $entityManager->getRepository(TimeMachine::class)->find($id);

        $timeMachine->setName($request->request->get('name'));
        $timeMachine->setResourceUrl($request->request->get('resourceUrl'));

        $entityManager->flush();

        return new JsonResponse([
            'id' => $timeMachine->getId(),
            'name' => $timeMachine->getName(),
            'resourceUrl' => $timeMachine->getResourceUrl(),
        ]);
    }

    #[Route('/time-machine/{id}', name: 'delete_time_machine', methods: ['DELETE'])]
    public function deleteTimeMachine(ManagerRegistry $managerRegistry, int $id): JsonResponse
    {
        $entityManager = $managerRegistry->getManager();
        $timeMachine = $entityManager->getRepository(TimeMachine::class)->find($id);

        $entityManager->remove($timeMachine);
        $entityManager->flush();

        return new JsonResponse([
            'id' => $timeMachine->getId(),
            'name' => $timeMachine->getName(),
            'resourceUrl' => $timeMachine->getResourceUrl(),
        ]);
    }

    #[Route('/time-machines', name: 'get_time_machines', methods: ['GET'])]
    public function getTimeMachines(ManagerRegistry $managerRegistry): JsonResponse
    {
        $timeMachines = $managerRegistry->getRepository(TimeMachine::class)->findAll();

        $response = [];
        foreach ($timeMachines as $timeMachine) {
            $response[] = [
                'id' => $timeMachine->getId(),
                'name' => $timeMachine->getName(),
                'resourceUrl' => $timeMachine->getResourceUrl(),
            ];
        }

        return new JsonResponse($response);
    }


}
