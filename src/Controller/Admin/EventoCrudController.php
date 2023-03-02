<?php

namespace App\Controller\Admin;

use App\Entity\Evento;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EventoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Evento::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        if(Crud::PAGE_EDIT == $pageName || Crud::PAGE_NEW == $pageName){
            return [
                TextField::new('nombre'),
                TextField::new('descrip'),
                DateField::new('fecha'),
                //AssociationField::new('participas'),
                ImageField::new('img')
                    ->setBasePath('images/eventos/')
                    ->setUploadDir('public/images/eventos/'),
            ];
        }
        return [
            IdField::new('id'),//->onlyOnForms(),
            TextField::new('nombre'),
            TextField::new('descrip'),
            DateField::new('fecha'),
            //AssociationField::new('participas'),
            ImageField::new('img')
                ->setBasePath('images/eventos/')
                ->setUploadDir('public/images/eventos/'),
        ];
    }
    
}
