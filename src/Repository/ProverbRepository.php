<?php

namespace App\Repository;

use App\Dto\ProverbDto;
use App\Entity\Proverb;
use App\Entity\Topic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Proverb>
 */
class ProverbRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Proverb::class);
    }

    public function add(ProverbDto $dto): ?Proverb
    {
        $proverb = new Proverb();
        $proverb->setContent($dto->content);
        $proverb->setTopic($this->getEntityManager()->getRepository(Topic::class)->find($dto->topic));

        $this->getEntityManager()->persist($proverb);
        $this->getEntityManager()->flush();

        return $proverb;
    }
}
