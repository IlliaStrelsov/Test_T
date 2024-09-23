<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entity\Region;
use Doctrine\ORM\EntityManagerInterface;

class RegionRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function existsByLatLon(float $lat, float $lon): bool
    {
        $qb = $this->entityManager->createQueryBuilder();
        return (bool) $qb
            ->select('COUNT(r.id)')
            ->from(Region::class, 'r')
            ->where('r.lat = :lat')
            ->andWhere('r.lon = :lon')
            ->setParameter('lat', $lat)
            ->setParameter('lon', $lon)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function saveRegion(string $name, float $lat, float $lon): void
    {
        $oblast = new Region();
        $oblast->setName($name)
            ->setLat($lat)
            ->setLon($lon);

        $this->entityManager->persist($oblast);
        $this->entityManager->flush();
    }

    public function findByCoordinates(float $lat, float $lon): ?Region
    {
        $qb = $this->entityManager->createQueryBuilder();
        return $qb
            ->select('r')
            ->from(Region::class, 'r')
            ->where('r.lat = :lat')
            ->andWhere('r.lon = :lon')
            ->setParameter('lat', $lat)
            ->setParameter('lon', $lon)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
