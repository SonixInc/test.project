<?php


namespace App\DataFixtures;


use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class CompanyFixtures
 *
 * @package App\DataFixtures
 */
class CompanyFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $company1 = new Company();
        $company1->setName('Sensio Labs');
        $company1->setLogo('sensio-labs.gif');
        $company1->setUrl('http://www.sensiolabs.com/');
        $company1->setActive(true);

        $company2 = new Company();
        $company2->setName('Extreme Sensio');
        $company2->setLogo('extreme-sensio.gif');
        $company2->setUrl('http://www.extreme-sensio.com/');
        $company2->setActive(true);

        $manager->persist($company1);
        $manager->persist($company2);

        $manager->flush();

        $this->addReference('sensio-labs', $company1);
        $this->addReference('extreme-sensio', $company2);
    }
}