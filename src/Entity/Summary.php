<?php


namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Summary
 *
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\SummaryRepository")
 * @ORM\Table(name="summaries")
 * @ApiResource(
 *     denormalizationContext={"groups"={"write"}},
 *     normalizationContext={"groups"={"read"}},
 *     attributes={"fetchEager": true},
 *     paginationEnabled=false
 * )
 */
class Summary
{
    public const EDUCATION_SECONDARY = 'secondary';
    public const EDUCATION_HIGHER = 'higher';

    public const EDUCATIONS = [
        self::EDUCATION_SECONDARY,
        self::EDUCATION_HIGHER,
    ];

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $lastName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $phone;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $city;

    /**
     * @var string
     * @ORM\Column(type="string", length=32)
     * @Groups({"read", "write"})
     */
    private $sex;

    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="summaries")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * @Groups({"read", "write"})
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32)
     * @Groups({"read", "write"})
     */
    private $education;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="summaries")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     *
     * @Groups({"read", "write"})
     */
    private $user;

    /**
     * @var ArrayCollection|Job[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Job", mappedBy="summaries")
     *
     * @Groups("read")
     */
    private $jobs;

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param $fistName
     *
     * @return $this
     */
    public function setFirstName(string $fistName): self
    {
        $this->firstName = $fistName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param $lastName
     *
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * @return string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param $phone
     *
     * @return $this
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param $city
     *
     * @return $this
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSex(): ?string
    {
        return $this->sex;
    }

    /**
     * @param $sex
     *
     * @return $this
     */
    public function setSex(string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     *
     * @return $this
     */
    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string
     */
    public function getEducation(): ?string
    {
        return $this->education;
    }

    /**
     * @param $education
     *
     * @return $this
     */
    public function setEducation(string $education): self
    {
        $this->education = $education;

        return $this;
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
     * @return Job[]|ArrayCollection
     */
    public function getJobs(): ?array
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
     * @return self
     */
    public function removeJob(Job $job): self
    {
        $this->jobs->removeElement($job);

        return $this;
    }
}