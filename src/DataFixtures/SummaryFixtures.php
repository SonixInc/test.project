<?php


namespace App\DataFixtures;


use App\Entity\Category;
use App\Entity\Summary;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class SummaryFixtures
 *
 * @package App\DataFixtures
 */
class SummaryFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        /**
         * @var Category $categoryDesign
         * @var Category $categoryProgramming
         */
        $categoryDesign = $this->getReference('category-design');
        $categoryProgramming = $this->getReference('category-programming');

        /**
         * @var User $user
         * @var User $admin
         */
        $user = $this->getReference('user');
        $admin = $this->getReference('admin');

        $summary1 = new Summary();
        $summary1->setFirstName('John');
        $summary1->setLastName('Smith');
        $summary1->setPhone('8 800 888-88-88');
        $summary1->setCity('Novosibirsk');
        $summary1->setSex('Male');
        $summary1->setCategory($categoryDesign);
        $summary1->setEducation(Summary::EDUCATION_SECONDARY);
        $summary1->setUser($user);

        $summary2 = new Summary();
        $summary2->setFirstName('James');
        $summary2->setLastName('Bond');
        $summary2->setPhone('8 800 888-88-88');
        $summary2->setCity('New York');
        $summary2->setSex('Male');
        $summary2->setCategory($categoryProgramming);
        $summary2->setEducation(Summary::EDUCATION_HIGHER);
        $summary2->setUser($admin);

        $manager->persist($summary1);
        $manager->persist($summary2);

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            UserFixture::class,
            CategoryFixtures::class,
        ];
    }
}