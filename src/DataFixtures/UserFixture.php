<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixture
 *
 * @package App\DataFixtures
 */
class UserFixture extends Fixture
{
    private $passwordEncoder;

    /**
     * UserFixture constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('user');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'secret'
        ));

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setRoles([User::ROLE_ADMIN]);
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'secret'
        ));

        $manager->persist($user);
        $manager->persist($admin);

        $manager->flush();

        $this->addReference('user', $user);
        $this->addReference('admin', $admin);
    }
}
