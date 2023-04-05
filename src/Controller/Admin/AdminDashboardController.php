<?php

namespace App\Controller\Admin;

use App\Entity\Animal;
use App\Entity\Client;
use App\Entity\Espece;
use App\Entity\Event;
use App\Entity\TypeEvent;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Admin Panel - Vetelib');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Client related stuff');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-tags', User::class);
        yield MenuItem::linkToCrud('Animaux', 'fa fa-tags', Animal::class);
        yield MenuItem::linkToCrud('Evenements', 'fa fa-tags', Event::class);

        yield MenuItem::section('Others');
        yield MenuItem::linkToCrud('Especes', 'fa fa-tags', Espece::class);
        yield MenuItem::linkToCrud('Types évènement', 'fa fa-tags', TypeEvent::class);
    }
}
