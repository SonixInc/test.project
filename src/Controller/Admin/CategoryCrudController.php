<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field;

class CategoryCrudController extends AbstractCrudController
{
    /**
     * Get entity name
     *
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Category::class;
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
            Field\TextField::new('name'),
            Field\TextField::new('slug', 'Position')->hideOnForm(),
            Field\IntegerField::new('jobCount', 'Jobs')->hideOnForm(),
            Field\IntegerField::new('affiliateCount', 'Affiliates')->hideOnForm(),
        ];
    }
}
