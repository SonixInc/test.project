<?php

namespace App\Controller\Admin;

use App\Entity\Summary;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field;

/**
 * Class SummaryCrudController
 *
 * @package App\Controller\Admin
 */
class SummaryCrudController extends AbstractCrudController
{
    /**
     * Get entity name
     *
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Summary::class;
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
            Field\TextField::new('fullName')->hideOnForm(),
            Field\TextField::new('firstName')->onlyOnForms(),
            Field\TextField::new('lastName')->onlyOnForms(),
            Field\TelephoneField::new('phone'),
            Field\TextField::new('city'),
            Field\ChoiceField::new('sex')->setChoices(['Male' => 'male', 'Female' => 'female']),
            Field\AssociationField::new('category'),
            Field\ChoiceField::new('education')->setChoices(array_combine(Summary::EDUCATIONS, Summary::EDUCATIONS)),
        ];
    }

}
