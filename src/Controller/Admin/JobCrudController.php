<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field;
use Symfony\Component\HttpFoundation\File\File;

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

        return [
            Field\IdField::new('id')->onlyOnIndex(),
            Field\ChoiceField::new('type')->setChoices(array_combine(Job::TYPES, Job::TYPES))->hideOnIndex(),
            Field\TextField::new('company'),
            Field\TextField::new('position'),
            Field\TextField::new('location'),
            Field\TextareaField::new('description')->hideOnIndex(),
            Field\TextField::new('howToApply', 'How to apply?')->hideOnIndex(),
            Field\BooleanField::new('public', 'Public?')->hideOnIndex(),
            Field\TextField::new('email'),
            Field\UrlField::new('url'),
            Field\BooleanField::new('activated'),
            Field\ImageField::new('logo')->formatValue(function ($value) {
                if (!empty($value)) {
                    $value = new File($value);
                    return $this->getParameter('jobs_web_directory') . '/' . $value->getFilename();
                }

                return $this->getParameter('jobs_web_directory') . '/' . 'dummy_image.jpeg';
            }),
            Field\ChoiceField::new('category')->setChoices($categories)->setFieldFqcn(Category::class)->hideOnIndex()
        ];
    }

}
