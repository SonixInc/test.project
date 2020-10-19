<?php

namespace App\Controller\Admin;

use App\Entity\Affiliate;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field;

/**
 * Class AffiliateCrudController
 *
 * @package App\Controller\Admin
 */
class AffiliateCrudController extends AbstractCrudController
{
    /**
     * Get entity name
     *
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Affiliate::class;
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
            Field\EmailField::new('email'),
            Field\UrlField::new('url', 'URL'),
            Field\BooleanField::new('active')->renderAsSwitch(false),
            Field\AssociationField::new('categories')->renderAsNativeWidget()->hideOnIndex()
        ];
    }

    /**
     * Action configuration
     *
     * @param Actions $actions
     *
     * @return Actions
     */
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
