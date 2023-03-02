<?php

namespace App\Controller\Admin;

use App\Entity\Juego;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class JuegoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Juego::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        if(Crud::PAGE_EDIT == $pageName || Crud::PAGE_NEW == $pageName){
            return [
                TextField::new('nombre'),
                TextField::new('editorial'),
                ImageField::new('img')
                    ->setBasePath('images/juegos/')
                    ->setUploadDir('public/images/juegos/'),
                IntegerField::new('ancho'),
                IntegerField::new('longitud'),
                IntegerField::new('minJuga'),
                IntegerField::new('maxJuga'),
            ];
        }
        return [
            IdField::new('id'),//->onlyOnForms(),
            TextField::new('nombre'),
            TextField::new('editorial'),
            ImageField::new('img')
                ->setBasePath('images/juegos/')
                ->setUploadDir('public/images/juegos/'),
            IntegerField::new('ancho'),
            IntegerField::new('longitud'),
            IntegerField::new('minJuga'),
            IntegerField::new('maxJuga'),
        ];
    }
    
}
