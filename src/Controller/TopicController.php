<?php

namespace App\Controller;

use App\Dto\ListFilterDto;
use App\Dto\TopicDto;
use App\Entity\Topic;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;

class TopicController extends AbstractController
{
    #[Route('api/topics', name: 'app_topic_list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Topic list',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Topic::class, groups: ['topic']))
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
    #[OA\Tag(name: 'Topics')]
    public function list(#[MapQueryString] ListFilterDto $listFilterDto, TopicRepository $topicRepository, SerializerInterface $serializer): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $serializer->serialize(
                $topicRepository->findBy([], [$listFilterDto->order_by ?? 'label' => 'ASC'], $listFilterDto->limit, $listFilterDto->limit * ($listFilterDto->page - 1)),
                'json', [
                    'groups' => ['topic']
                ]
            )
        );
    }

    #[Route('api/topics/{id}', name: 'app_topic_detail', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Topic detail',
        content: new Model(type: Topic::class, groups: ['topic'])
    )]
    #[OA\Tag(name: 'Topics')]
    public function detail(Topic $topic, SerializerInterface $serializer): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $serializer->serialize(
                $topic,
                'json', [
                    'groups' => ['topic']
                ]
            )
        );
    }

    #[Route('api/topics', name: 'app_topic_add', methods: ['POST'])]
    #[OA\Tag(name: 'Topics')]
    public function add(
        #[MapRequestPayload] TopicDto $topicDto,
        SerializerInterface $serializer,
        TopicRepository $topicRepository,
    ): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $serializer->serialize(
                $topicRepository->add($topicDto),
                'json', [
                    'groups' => ['topic']
                ]
            )
        );
    }

    #[Route('api/topics/{id}', name: 'app_topic_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Topics')]
    public function delete(
        ?Topic $topic,
        EntityManagerInterface $entityManager,
    ): JsonResponse
    {
        try {
            if (!$topic) {
                return new JsonResponse('Topic not found');
            }

            $entityManager->remove($topic);
            $entityManager->flush();

            return new JsonResponse('Topic deleted with success');
        } catch (\Exception $e) {
            return new JsonResponse('Topic deleted with error');
        }
    }
}