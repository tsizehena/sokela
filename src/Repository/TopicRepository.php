<?php

namespace App\Repository;

use App\Dto\TopicDto;
use App\Entity\Topic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Topic>
 */
class TopicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Topic::class);
    }

    public function add(TopicDto $dto)
    {
        $topic = $this->findOneBy(['label' => $dto->label]) ?? new Topic();
        $topic->setLabel($dto->label);

        $this->getEntityManager()->persist($topic);
        $this->getEntityManager()->flush();

        return $topic;
    }
}
