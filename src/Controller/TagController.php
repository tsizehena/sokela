<?php

namespace App\Controller;

use App\Dto\ListFilterDto;
use App\Dto\TagDto;
use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;

class TagController extends AbstractController
{
    #[Route('api/tags', name: 'app_tag_list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Tag list',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Tag::class, groups: ['tag']))
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
    #[OA\Tag(name: 'Tags')]
    public function list(#[MapQueryString] ListFilterDto $listFilterDto, TagRepository $tagRepository, SerializerInterface $serializer): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $serializer->serialize(
                $tagRepository->findBy([], [$listFilterDto->order_by ?? 'name' => 'ASC'], $listFilterDto->limit, $listFilterDto->limit * ($listFilterDto->page - 1)),
                'json', [
                    'groups' => ['topic']
                ]
            )
        );
    }

    #[Route('api/tags/{id}', name: 'app_tag_detail', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Tag detail',
        content: new Model(type: Tag::class, groups: ['tag'])
    )]
    #[OA\Tag(name: 'Tags')]
    public function detail(Tag $tag, SerializerInterface $serializer): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $serializer->serialize(
                $tag,
                'json', [
                    'groups' => ['tag']
                ]
            )
        );
    }

    #[Route('api/tags', name: 'app_tag_add', methods: ['POST'])]
    #[OA\Tag(name: 'Tags')]
    public function add(
        #[MapRequestPayload] TagDto $tagDto,
        SerializerInterface $serializer,
        TagRepository $tagRepository,
    ): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $serializer->serialize(
                $tagRepository->add($tagDto),
                'json', [
                    'groups' => ['tag']
                ]
            )
        );
    }

    #[Route('api/tags/{id}', name: 'app_tag_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Tags')]
    public function delete(
        ?Tag $tag,
        EntityManagerInterface $entityManager,
    ): JsonResponse
    {
        try {
            if (!$tag) {
                return new JsonResponse('Tag not found');
            }

            $entityManager->remove($tag);
            $entityManager->flush();

            return new JsonResponse('Tag deleted with success');
        } catch (\Exception $e) {
            return new JsonResponse('Tag deleted with error');
        }
    }
}