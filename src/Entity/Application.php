<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Application
 *
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\ApplicationRepository")
 * @ORM\Table(name="jobs_summaries", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"job_id", "summary_id"})
 * })
 */
class Application
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Job", inversedBy="applications")
     * @ORM\JoinColumn(name="job_id", nullable=false, onDelete="CASCADE")
     */
    private $job;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Summary", inversedBy="applications")
     * @ORM\JoinColumn(name="summary_id", nullable=false, onDelete="CASCADE")
     */
    private $summary;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $viewed;

    /**
     * JobApplication constructor.
     */
    public function __construct()
    {
        $this->viewed = false;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Job
     */
    public function getJob(): ?Job
    {
        return $this->job;
    }

    /**
     * @param Job $job
     *
     * @return $this
     */
    public function setJob(Job $job): self
    {
        $this->job = $job;

        return $this;
    }

    /**
     * @return Summary
     */
    public function getSummary(): ?Summary
    {
        return $this->summary;
    }

    /**
     * @param Summary $summary
     *
     * @return $this
     */
    public function setSummary(Summary $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * @param bool $viewed
     *
     * @return $this
     */
    public function setViewed(bool $viewed): self
    {
        $this->viewed = $viewed;

        return $this;
    }

    /**
     * @return bool
     */
    public function isViewed(): ?bool
    {
        return $this->viewed;
    }
}