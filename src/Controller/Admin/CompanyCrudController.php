<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * Class CompanyCrudController
 *
 * @package App\Controller\Admin
 */
class CompanyCrudController extends AbstractCrudController
{
    /**
     * Get entity name
     *
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Company::class;
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
        $logoFile = Field\ImageField::new('logo', 'Logo')
            ->setBasePath($this->getParameter('companies_web_directory'))
            ->setLabel('Logo');

        $logo = Field\ImageField::new('logoFile', 'Logo File')
            ->setFormType(VichImageType::class)
            ->setFormTypeOptions([
                'allow_delete' => false,
                'delete_label' => 'Delete image ?'
            ]);

        $fields = [
            Field\IdField::new('id')->hideOnForm(),
            Field\TextField::new('name'),
            Field\UrlField::new('url'),
            Field\BooleanField::new('active'),
            Field\AssociationField::new('jobs')->hideOnForm(),
            Field\AssociationField::new('feedbacks')->hideOnForm(),
        ];

        if ($pageName === Crud::PAGE_INDEX || $pageName === Crud::PAGE_DETAIL) {
            $fields[] = $logoFile;
        } else {
            $fields[] = $logo;
        }

        return $fields;
    }

}
