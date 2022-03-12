<?php

namespace App\Repository;

use App\ApiData\ApiRequest;
use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    const LIMIT = 50;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function add(Player $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Player $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findByFilter(ApiRequest $request): array
    {
        $qb = $this->createQueryBuilder('p');

        $criteria = $request->has("or")
            ? $qb->expr()->orX()
            : $qb->expr()->andX();

        if ($request->has("country")) {
            $criteria->add(
                $qb->expr()->like(
                    'p.country',
                    $qb->expr()->literal('%' . $request->get("country") . '%')
                )
            );
        }

        if ($request->has("position")) {
            $criteria->add(
                $qb->expr()->like(
                    'p.position',
                    $qb->expr()->literal('%' . $request->get("position") . '%')
                )
            );
        }

        if ($criteria->count()) {
            $qb->where($criteria);
        }

        $limit = $request->has('limit') && $request->get('limit') <= self::LIMIT
            ? $request->get('limit')
            : self::LIMIT;
        $offset = $request->has('offset') ? $request->get('offset') : 0;

        return $qb
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
