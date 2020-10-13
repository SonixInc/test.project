<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Form\Type\VichImageType;

class JobCrudController extends AbstractCrudController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getEntityFqcn(): string
    {
        return Job::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $categories = [];

        /** @var Category $category */
        foreach ($this->em->getRepository(Category::class)->findAll() as $category) {
            $categories[$category->getName()] = $category;
        }

        $fields = [
            Field\IdField::new('id')->onlyOnIndex(),
            Field\ChoiceField::new('type')->setChoices(array_combine(Job::TYPES, Job::TYPES))->hideOnIndex(),
            Field\TextField::new('company'),
            Field\TextField::new('position'),
            Field\TextField::new('location'),
            Field\TextEditorField::new('description')->hideOnIndex(),
            Field\TextField::new('howToApply', 'How to apply?')->hideOnIndex(),
            Field\BooleanField::new('public', 'Public?')->hideOnIndex(),
            Field\TextField::new('email'),
            Field\UrlField::new('url'),
            Field\BooleanField::new('activated'),
            Field\ChoiceField::new('category')->setChoices($categories)->setFieldFqcn(Category::class)->hideOnIndex()
        ];

        if ($pageName === Crud::PAGE_INDEX || $pageName === Crud::PAGE_DETAIL) {
            $fields[] = Field\ImageField::new('logo', 'Logo')->setBasePath($this->getParameter('jobs_web_directory'));
        } else {
            $fields[] = Field\ImageField::new('logoFile', 'Logo File')->setFormType(VichImageType::class)
                ->setFormTypeOptions([
                    'allow_delete' => true,
                    'required' => false,
                    'delete_label' => 'Delete image ?'
                ]);
        }
        return $fields;
    }

}
