<?php

namespace App\Controller;

use App\Dto\ListFilterDto;
use App\Dto\ProverbDto;
use App\Entity\Proverb;
use App\Repository\ProverbRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;

final class ProverbController extends AbstractController
{
    #[Route('api/proverbs', name: 'app_proverb_list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Proverb list',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Proverb::class, groups: ['proverb', 'tag', 'topic']))
        )
    )]
    #[OA\Parameter(
        name: 'limit',
        description: 'Limit number of results',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'integer', default: 10)
    )]
    #[OA\Parameter(
        name: 'page',
        description: 'Page number',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'integer', default: 1)
    )]
    #[OA\Parameter(
        name: 'query',
        description: 'Query',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'string', nullable: true)
    )]
    #[OA\Parameter(
        name: 'order_by',
        description: 'Order by',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'string', nullable: true)
    )]
    #[OA\Tag(name: 'Proverbs')]
    public function list(#[MapQueryString] ListFilterDto $listFilterDto, ProverbRepository $proverbRepository, SerializerInterface $serializer): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $serializer->serialize(
                $proverbRepository->findBy([], [$listFilterDto->order_by ?? 'content' => 'ASC'], $listFilterDto->limit, $listFilterDto->limit * ($listFilterDto->page - 1)),
                'json', [
                    'groups' => ['proverb', 'tag', 'topic']
                ]
            )
        );
    }

    #[Route('api/proverbs/{id}', name: 'app_proverb_detail', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Proverb detail',
        content: new Model(type: Proverb::class, groups: ['proverb', 'tag', 'topic'])
    )]
    #[OA\Tag(name: 'Proverbs')]
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

    #[Route('api/proverbs', name: 'app_proverb_add', methods: ['POST'])]
    #[OA\Tag(name: 'Proverbs')]
    public function add(
        #[MapRequestPayload]  ProverbDto $proverbDto,
        SerializerInterface $serializer,
        ProverbRepository $proverbRepository,
    ): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $serializer->serialize(
                $proverbRepository->add($proverbDto),
                'json', [
                    'groups' => ['proverb', 'tag', 'topic']
                ]
            )
        );
    }

    #[Route('api/proverbs/{id}', name: 'app_proverb_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Proverbs')]
    public function delete(
        ?Proverb $proverb,
        EntityManagerInterface $entityManager,
    ): JsonResponse
    {
        try {
            if (!$proverb) {
                return new JsonResponse('Proverb not found');
            }

            $entityManager->remove($proverb);
            $entityManager->flush();

            return new JsonResponse('Proverb deleted with success');
        } catch (\Exception $e) {
            return new JsonResponse('Proverb deleted with error');
        }

    }
}
