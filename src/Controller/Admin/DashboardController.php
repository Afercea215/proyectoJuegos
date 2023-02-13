<?php

namespace App\Controller\Admin;

use App\Entity\Disposicion;
use App\Entity\Evento;
use App\Entity\Juego;
use App\Entity\Reserva;
use App\Entity\Tramo;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\EventDispatcher\Event;

class DashboardController extends AbstractDashboardController
{
    //#[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();

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
        return $this->render('/admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ProyectoJuegos');
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
                ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToRoute('Home', 'fa fa-home', 'home'),
            MenuItem::linkToDashboard('Dashboard', 'fa fa-dashboard'),
            MenuItem::subMenu('Admin', 'fa-solid fa-folder')->setSubItems([
                //MenuItem::linkToRoute('Home', 'fa ...', 'home'),
                /* MenuItem::linkToCrud('Editar', 'fa ...', User::class)->setAction('edit'),
                MenuItem::linkToCrud('Borrar', 'fa ...', User::class)->setAction('delete'), */
                MenuItem::linkToCrud('User', 'fa fa-user ', User::class),
                MenuItem::linkToCrud('Evento', 'fa-solid fa-calendar-days', Evento::class),
                MenuItem::linkToCrud('Disposicion', 'fa-solid fa-map-pin', Disposicion::class),
                MenuItem::linkToCrud('Juego', 'fa-solid fa-gamepad', Juego::class),
                MenuItem::linkToCrud('Reserva', 'fa-solid fa-book', Reserva::class),
                MenuItem::linkToCrud('Tramo', 'fa-solid fa-hourglass-half', Tramo::class),
                //MenuItem::linkToUrl('Search in Google', 'fab fa-google', 'https://google.com'),
            ]),

        ];
            
            
        //yield MenuItem::linkToCrud('The Label', 'fas fa-list', User::class);
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        // Usually it's better to call the parent method because that gives you a
        // user menu with some menu items already created ("sign out", "exit impersonation", etc.)
        // if you prefer to create the user menu from scratch, use: return UserMenu::new()->...
        return parent::configureUserMenu($user)
            // use the given $user object to get the user name
            ->setName($user->getUserIdentifier())
            // use this method if you don't want to display the name of the user
            //->displayUserName(false)

            // you can return an URL with the avatar image
            //->setAvatarUrl('https://...')
            //->setAvatarUrl($user->getProfileImageUrl())
            // use this method if you don't want to display the user image
            ->displayUserAvatar(false)
            // you can also pass an email address to use gravatar's service
            //->setGravatarEmail($user->getMainEmailAddress())

            // you can use any type of menu item, except submenus
            ->addMenuItems([
                MenuItem::linkToRoute('My Profile', 'fa fa-id-card', '...', ['...' => '...']),
                MenuItem::linkToRoute('Settings', 'fa fa-user-cog', '...', ['...' => '...']),
                MenuItem::section(),
                MenuItem::linkToLogout('Logout', 'fa fa-sign-out'),
            ]);
    }
}
