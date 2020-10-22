<?php


namespace App\Entity;

use App\Controller\Api\Company\LogoUploadController;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class Company
 *
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 * @ORM\Table(name="company")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable()
 * @ApiResource(
 *     denormalizationContext={"groups"={"write"}},
 *     normalizationContext={"groups"={"read"}},
 *     attributes={"fetchEager": true},
 *     paginationEnabled=false,
 *     collectionOperations={
 *          "get",
 *          "post"={
 *             "controller"=LogoUploadController::class,
 *             "deserialize"=false,
 *             "openapiContext"={
 *                  "requestBody"={
 *                      "content"={
 *                          "multipart/form-data"={
 *                              "schema"={
 *                                  "type"="object",
 *                                  "properties"={
 *                                      "logoFile"={
 *                                          "type"="string"
 *                                      }
 *                                  }
 *                              }
 *                          }
 *                      }
 *                  }
 *              }
 *          }
 *     }
 * )
 */
class Company
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     *
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Groups({"read", "write"})
     */
    private $name;
    /**
     * @var string|null|UploadedFile
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Groups("read")
     */
    private $logo;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="jobs", fileNameProperty="logo")
     *
     * @Groups("write")
     */
    private $logoFile;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Groups({"read", "write"})
     */
    private $url;

    /**
     * @var ArrayCollection|Job[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Job", mappedBy="company", cascade={"remove"})
     *
     * @Groups("read")
     */
    private $jobs;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     *
     * @Groups("read")
     */
    private $active;

    /**
     * @var ArrayCollection|Feedback[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Feedback", mappedBy="company", cascade={"all"})
     *
     * @Groups("read")
     */
    private $feedbacks;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="companies")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->active = false;
        $this->jobs = new ArrayCollection();
        $this->feedbacks = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|UploadedFile|null
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string|null|UploadedFile $logo
     *
     * @return self
     */
    public function setLogo($logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return File
     */
    public function getLogoFile(): ?File
    {
        return $this->logoFile;
    }

    /**
     * @param File $logoFile
     *
     * @return self
     */
    public function setLogoFile(File $logoFile): self
    {
        $this->logoFile = $logoFile;

        if ($logoFile) {
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     *
     * @return self
     */
    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Job[]|ArrayCollection
     */
    public function getJobs(): array
    {
        return $this->jobs->toArray();
    }

    /**
     * @param Job $job
     *
     * @return self
     */
    public function addJob(Job $job): self
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs->add($job);
        }

        return $this;
    }

    /**
     * @param Job $job
     *
     * @return $this
     */
    public function removeJob(Job $job): self
    {
        $this->jobs->removeElement($job);

        return $this;
    }

    /**
     * @return Feedback[]|ArrayCollection
     */
    public function getFeedbacks(): array
    {
        return $this->feedbacks->toArray();
    }

    /**
     * @param Feedback $feedback
     *
     * @return $this
     */
    public function addFeedbacks(Feedback $feedback): self
    {
        $this->feedbacks->add($feedback);

        return $this;
    }

    /**
     * @param Feedback $feedback
     *
     * @return $this
     */
    public function removeFeedback(Feedback $feedback): self
    {
        $this->feedbacks->removeElement($feedback);

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     *
     * @return self
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist(): void
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function __toString(): string
    {
        return $this->name;
    }
}