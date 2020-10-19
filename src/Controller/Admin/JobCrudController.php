<?php

namespace App\Controller\Admin;

use App\Entity\Job;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * Class JobCrudController
 *
 * @package App\Controller\Admin
 */
class JobCrudController extends AbstractCrudController
{
    /**
     * Get entity name
     *
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Job::class;
    }

    /**
     * CRUD configuration
     *
     * @param Crud $crud
     *
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPaginatorPageSize($this->getParameter('max_per_page'))
            ->overrideTemplate('crud/index', 'bundles/easyadmin/index.html.twig');
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
            Field\IdField::new('id')->onlyOnIndex(),
            Field\ChoiceField::new('type')->setChoices(array_combine(Job::TYPES, Job::TYPES))->hideOnIndex(),
            Field\AssociationField::new('company'),
            Field\TextField::new('position'),
            Field\TextField::new('location'),
            Field\TextEditorField::new('description')->hideOnIndex(),
            Field\TextField::new('howToApply', 'How to apply?')->hideOnIndex(),
            Field\BooleanField::new('public', 'Public?')->hideOnIndex(),
            Field\TextField::new('email'),
            Field\BooleanField::new('activated'),
            Field\AssociationField::new('category'),
            Field\DateTimeField::new('createdAt')->hideOnForm()
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
