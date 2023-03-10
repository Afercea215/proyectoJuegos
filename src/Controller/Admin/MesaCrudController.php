<?php

namespace App\Controller\Admin;

use App\Entity\Mesa;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class MesaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Mesa::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        if(Crud::PAGE_EDIT == $pageName || Crud::PAGE_NEW == $pageName){
            return [
                IntegerField::new('ancho'),
                IntegerField::new('longitud'),
                IntegerField::new('x'),
                IntegerField::new('y'),
            ];
        }
        return [
            IdField::new('id'),
            IntegerField::new('ancho'),
            IntegerField::new('longitud'),
            IntegerField::new('x'),
            IntegerField::new('y'),
        ];
    }

}
