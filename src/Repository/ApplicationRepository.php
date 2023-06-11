<?php

declare(strict_types=1);

namespace Hemonugi\ToolKitTestAssignment\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Cache\CacheException;
use Doctrine\Persistence\ManagerRegistry;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ApplicationInterface;
use Hemonugi\ToolKitTestAssignment\Domain\Application\ApplicationRepositoryInterface;
use Hemonugi\ToolKitTestAssignment\Domain\Application\Dto\CreateDto;
use Hemonugi\ToolKitTestAssignment\Domain\Application\Dto\GetListDto;
use Hemonugi\ToolKitTestAssignment\Entity\Application;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Clock\Clock;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

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
    private const CACHE_LIST = 'application-list';

    public function __construct(ManagerRegistry $registry, private readonly TagAwareCacheInterface $cache)
    {
        parent::__construct($registry, Application::class);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function save(Application $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        $this->cache->invalidateTags([self::CACHE_LIST]);
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
     * @throws CacheException
     * @throws InvalidArgumentException
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

        if ($listDto->creatorId !== null) {
            $queryBuilder->andWhere('a.creator = :creator_id')
                ->setParameter(':creator_id', $listDto->creatorId);
        }

        $query = $queryBuilder->getQuery();
        $key = md5($query->getSQL());

        return $this->cache->get($key, function (ItemInterface $item) use ($query) {
            $item->tag([self::CACHE_LIST]);

            return $query->getResult();
        });
    }

    /**
     * @inheritDoc
     */
    public function create(CreateDto $dto): ApplicationInterface
    {
        $application = Application::create($dto, Clock::get());
        $this->save($application, true);

        return $application;
    }
}
