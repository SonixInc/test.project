<?php


namespace App\DataFixtures;


use App\Entity\Category;
use App\Entity\Company;
use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class JobFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager) : void
    {
        /** @var Category $categoryProgramming */
        $categoryProgramming = $manager->merge($this->getReference('category-programming'));

        /**
         * @var Company $company1
         * @var Company $company2
         */
        $company1 = $this->getReference('sensio-labs');
        $company2 = $this->getReference('extreme-sensio');



        $jobSensioLabs = new Job();
        $jobSensioLabs->setCategory($categoryProgramming);
        $jobSensioLabs->setType('full-time');
        $jobSensioLabs->setCompany($company1);
        $jobSensioLabs->setPosition('Web Developer');
        $jobSensioLabs->setLocation('Paris, France');
        $jobSensioLabs->setDescription('You\'ve already developed websites with symfony and you want to work with Open-Source technologies. You have a minimum of 3 years experience in web development with PHP or Java and you wish to participate to development of Web 2.0 sites using the best frameworks available.');
        $jobSensioLabs->setHowToApply('Send your resume to fabien.potencier [at] sensio.com');
        $jobSensioLabs->setPublic(true);
        $jobSensioLabs->setActivated(true);
        $jobSensioLabs->setToken('job_sensio_labs');
        $jobSensioLabs->setEmail('job@example.com');
        $jobSensioLabs->setExpiresAt(new \DateTime('+30 days'));

        $jobExtremeSensio = new Job();
        /** @var Category $categoryDesign */
        $categoryDesign = $manager->merge($this->getReference('category-design'));
        $jobExtremeSensio->setCategory($categoryDesign);
        $jobExtremeSensio->setType('part-time');
        $jobExtremeSensio->setCompany($company2);
        $jobExtremeSensio->setPosition('Web Designer');
        $jobExtremeSensio->setLocation('Paris, France');
        $jobExtremeSensio->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in.');
        $jobExtremeSensio->setHowToApply('Send your resume to fabien.potencier [at] sensio.com');
        $jobExtremeSensio->setPublic(true);
        $jobExtremeSensio->setActivated(true);
        $jobExtremeSensio->setToken('job_extreme_sensio');
        $jobExtremeSensio->setEmail('job@example.com');
        $jobExtremeSensio->setExpiresAt(new \DateTime('+30 days'));

        $jobExpired = new Job();
        $jobExpired->setCategory($categoryProgramming);
        $jobExpired->setType('full-time');
        $jobExpired->setCompany($company2);
        $jobExpired->setPosition('Web Developer Expired');
        $jobExpired->setLocation('Paris, France');
        $jobExpired->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
        $jobExpired->setHowToApply('Send your resume to lorem.ipsum [at] dolor.sit');
        $jobExpired->setPublic(true);
        $jobExpired->setActivated(true);
        $jobExpired->setToken('job_expired');
        $jobExpired->setEmail('job@example.com');
        $jobExpired->setExpiresAt(new \DateTime('-10 days'));

        for ($i = 100; $i <= 130; $i++) {
            $job = new Job();
            $job->setCategory($categoryProgramming);
            $job->setType('full-time');
            $job->setCompany($company1);
            $job->setPosition('Web Developer');
            $job->setLocation('Paris, France');
            $job->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
            $job->setHowToApply('Send your resume to lorem.ipsum [at] dolor.sit');
            $job->setPublic(true);
            $job->setActivated(true);
            $job->setToken('job_' . $i);
            $job->setEmail('job@example.com');

            $manager->persist($job);
        }

        $manager->persist($jobSensioLabs);
        $manager->persist($jobExtremeSensio);
        $manager->persist($jobExpired);

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            CompanyFixtures::class,
            CategoryFixtures::class,
        ];
    }
}