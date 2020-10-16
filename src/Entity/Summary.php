<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Summary
 *
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\SummaryRepository")
 * @ORM\Table(name="summaries")
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
     */

    private $fistName;
    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */

    private $lastName;
    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */

    private $phone;
    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */

    private $city;
    /**
     * @var string
     * @ORM\Column(type="string", length=32)
     */

    private $sex;

    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="summaries")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32)
     */
    private $education;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFistName(): ?string
    {
        return $this->fistName;
    }

    /**
     * @param $fistName
     *
     * @return $this
     */
    public function setFistName(string $fistName): self
    {
        $this->fistName = $fistName;

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
}