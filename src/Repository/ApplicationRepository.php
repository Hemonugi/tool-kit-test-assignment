<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ApplicationInterface;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ApplicationRepositoryInterface;
use Hemonugi\ToolKitTestAssignment\Domain\Application\GetListDto;
use Hemonugi\ToolKitTestAssignment\Entity\Application;

/**
 * @extends ServiceEntityRepository<Application>
 *
 * @method Application|null find($id, $lockMode = null, $lockVersion = null)
 * @method Application|null findOneBy(array $criteria, array $orderBy = null)
 * @method Application[]    findAll()
 * @method Application[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApplicationRepository extends ServiceEntityRepository implements ApplicationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Application::class);
    }

    public function save(Application $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Application $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @inheritDoc
     */
    public function getList(GetListDto $listDto): array
    {
         $queryBuilder = $this->createQueryBuilder('a')
            ->orderBy('a.create_date', 'DESC');

        if ($listDto->statuses !== null && count($listDto->statuses) > 0) {
            $queryBuilder->andWhere('a.status IN (:statuses)')
                ->setParameter(':statuses', $listDto->statuses);
        }
        if ($listDto->startDateTime !== null) {
            $queryBuilder->andWhere('a.create_date >= :start_date')
                ->setParameter(':start_date', $listDto->startDateTime->format('Y-m-d H:i:s'));
        }
        if ($listDto->endDateTime !== null) {
            $queryBuilder->andWhere('a.create_date <= :end_date')
                ->setParameter(':end_date', $listDto->endDateTime->format('Y-m-d H:i:s'));
        }

        return  $queryBuilder->getQuery()->getResult();
    }
}
