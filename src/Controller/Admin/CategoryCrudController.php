<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            Field\IdField::new('id')->onlyOnIndex(),
            Field\TextField::new('name'),
            Field\TextField::new('slug', 'Position')->onlyOnIndex(),
            Field\CollectionField::new('jobs')->formatValue(function ($value) {
                return !is_object($value) ? $value : '0';
            })->onlyOnIndex(),
            Field\CollectionField::new('affiliates')->formatValue(function ($value) {
                return !is_object($value) ? $value : '0';
            })->onlyOnIndex(),
        ];
    }

}
