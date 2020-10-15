<?php

namespace App\Controller\Admin;

use App\Entity\Affiliate;
use App\Entity\Category;
use App\Entity\Job;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * Redirect to job entities
     *
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();

        return $this->redirect($routeBuilder->setController(JobCrudController::class)->generateUrl());
    }

    /**
     * Dashboard configuration
     *
     * @return Dashboard
     */
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Work Com');
    }

    /**
     * Left menu configuration
     *
     * @return iterable
     */
    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linktoRoute('Homepage', 'fa fa-home', 'home'),

            MenuItem::section('Job'),
            MenuItem::linkToCrud('Categories', 'fa fa-tags', Category::class),
            MenuItem::linkToCrud('Jobs', 'fa fa-file-text', Job::class),
            MenuItem::linkToCrud('Affiliates', 'fa fa-grip-vertical', Affiliate::class),
        ];
    }
}
