<?php

namespace App\Controller\Admin;

use App\Entity\Subscription;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field;

/**
 * Class SubscriptionCrudController
 *
 * @package App\Controller\Admin
 */
class SubscriptionCrudController extends AbstractCrudController
{
    /**
     * Get entity name
     *
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Subscription::class;
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
            Field\IdField::new('id'),
            Field\TextField::new('customer_id', 'Customer ID'),
            Field\BooleanField::new('canceled')->renderAsSwitch(false),
            Field\DateTimeField::new('current_period_start'),
            Field\DateTimeField::new('current_period_end'),
            Field\AssociationField::new('user'),
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
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::EDIT);
    }

}
