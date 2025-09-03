<?php

namespace App\Controller;

use App\Entity\Proverb;
use App\Repository\ProverbRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class ProverbController extends AbstractController
{
    #[Route('api/proverbs', name: 'app_proverb_list', methods: ['GET'])]
    public function list(ProverbRepository $proverbRepository, SerializerInterface $serializer): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $serializer->serialize(
                $proverbRepository->findAll(),
                'json', [
                    'groups' => ['proverb', 'tag', 'topic']
                ]
            )
        );
    }

    #[Route('api/proverbs/{id}', name: 'app_proverb_detail', methods: ['GET'])]
    public function detail(Proverb $proverb, SerializerInterface $serializer): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $serializer->serialize(
                $proverb,
                'json', [
                    'groups' => ['proverb', 'tag', 'topic']
                ]
            )
        );
    }

    #[Route('api/proverbs/search/{criteria}', name: 'app_proverb_search', methods: ['GET'])]
    public function search(string $criteria, ProverbRepository $proverbRepository, SerializerInterface $serializer): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $serializer->serialize(
                $proverbRepository->findByContent($criteria),
                'json', [
                    'groups' => ['proverb', 'tag', 'topic']
                ]
            )
        );
    }
}
