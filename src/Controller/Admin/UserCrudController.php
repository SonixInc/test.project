<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field;

/**
 * Class UserCrudController
 *
 * @package App\Controller\Admin
 */
class UserCrudController extends AbstractCrudController
{
    /**
     * Get entity name
     *
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    /**
     * Fields configuration
     *
     * @param string $pageName
     *
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            Field\IdField::new('id')->hideOnForm(),
            Field\TextField::new('username'),
            Field\ChoiceField::new('roles')->setChoices([
                'User' => User::ROLE_USER,
                'Admin' => User::ROLE_ADMIN,
                'Worker' => User::ROLE_WORKER,
                'Company' => User::ROLE_COMPANY,
            ])->allowMultipleChoices(),
        ];
    }

}
