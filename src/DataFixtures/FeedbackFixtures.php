<?php


namespace App\DataFixtures;


use App\Entity\Company;
use App\Entity\Feedback;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class FeedbackFixtures
 *
 * @package App\DataFixtures
 */
class FeedbackFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        /**
         * @var User $user
         * @var User $admin
         */
        $user = $this->getReference('user');
        $admin = $this->getReference('admin');

        /**
         * @var Company $sensioLabsCompany
         * @var Company $extremeSensioCompany
         */
        $sensioLabsCompany = $this->getReference('sensio-labs');
        $extremeSensioCompany = $this->getReference('extreme-sensio');

        $feedback1 = new Feedback();
        $feedback1->setUser($user);
        $feedback1->setContent('Great company');
        $feedback1->setCompany($sensioLabsCompany);

        $feedback2 = new Feedback();
        $feedback2->setUser($admin);
        $feedback2->setContent('I dont like this company');
        $feedback2->setCompany($sensioLabsCompany);

        $feedback3 = new Feedback();
        $feedback3->setUser($user);
        $feedback3->setContent('WTF???');
        $feedback3->setCompany($extremeSensioCompany);

        $manager->persist($feedback1);
        $manager->persist($feedback2);
        $manager->persist($feedback3);

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            UserFixture::class,
            CompanyFixtures::class,
        ];
    }
}