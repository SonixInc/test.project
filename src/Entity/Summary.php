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
     * @var Application
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Application", mappedBy="summary")
     */
    private $applications;

    /**
     * Summary constructor.
     */
    public function __construct()
    {
        $this->applications = new ArrayCollection();
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
     * @return Application[]|ArrayCollection
     */
    public function getApplications(): ?array
    {
        return $this->applications->toArray();
    }

    /**
     * @param Application $application
     *
     * @return self
     */
    public function addApplication(Application $application): self
    {
        if (!$this->applications->contains($application)) {
            $this->applications->add($application);
        }

        return $this;
    }

    /**
     * @param Application $application
     *
     * @return $this
     */
    public function removeApplication(Application $application): self
    {
        $this->applications->removeElement($application);

        return $this;
    }
}