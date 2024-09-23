<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="jobs")
 */
class Job
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(name="created_ts", type="bigint")
     */
    private int $createdTs;

    /**
     * @ORM\Column(name="scheduled_for_ts", type="bigint")
     */
    private int $scheduledForTs;

    /**
     * @ORM\Column(type="integer")
     */
    private int $state;

    // Геттери та сеттери для полів
    public function getId(): int
    {
        return $this->id;
    }

    public function getCreatedTs(): int
    {
        return $this->createdTs;
    }

    public function setCreatedTs(int $createdTs): void
    {
        $this->createdTs = $createdTs;
    }

    public function getScheduledForTs(): int
    {
        return $this->scheduledForTs;
    }

    public function setScheduledForTs(int $scheduledForTs): void
    {
        $this->scheduledForTs = $scheduledForTs;
    }

    public function getState(): int
    {
        return $this->state;
    }

    public function setState(int $state): void
    {
        $this->state = $state;
    }
}
