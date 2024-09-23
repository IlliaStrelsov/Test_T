<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class JobRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function createJob(int $startedAt, int $delaySeconds, int $state): Job
    {
        $job = new Job();
        $job->setCreatedTs($startedAt);
        $job->setScheduledForTs($startedAt + $delaySeconds);

        $job->setState($state);

        $this->entityManager->persist($job);
        $this->entityManager->flush();

        return $job;
    }

    public function updateJobStatus(Job $job, int $state): void
    {
        $job->setState($state);

        $this->entityManager->flush();
    }

    public function getJobs(int $limit): array
    {
        $qb = $this->entityManager->createQueryBuilder();

        return $qb
            ->select('j.createdTs, j.scheduledForTs, j.state')
            ->from(Job::class, 'j')
            ->orderBy('j.createdTs', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getArrayResult();
    }

    public function findJobById(int $id): ?Job
    {
        return $this->entityManager->find(Job::class, $id);
    }
}
