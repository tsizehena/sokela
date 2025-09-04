<?php

namespace App\Repository;

use App\Dto\TagDto;
use App\Dto\TopicDto;
use App\Entity\Tag;
use App\Entity\Topic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function add(TagDto $dto)
    {
        $tag = $this->findOneBy(['name' => $dto->name]) ?? new Tag();
        $tag->setName($dto->name);

        $this->getEntityManager()->persist($tag);
        $this->getEntityManager()->flush();

        return $tag;
    }
}
