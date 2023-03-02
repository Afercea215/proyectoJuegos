<?php

namespace App\Controller\Admin;

use App\Entity\Disposicion;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class DisposicionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Disposicion::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        if(Crud::PAGE_EDIT == $pageName || Crud::PAGE_NEW == $pageName){
            return [
                DateField::new('fecha'),
                AssociationField::new('mesa'),
                IntegerField::new('x'),
                IntegerField::new('y'),
            ];
        }
        return [
            IdField::new('id'),//->onlyOnForms(),
            DateField::new('fecha'),
            AssociationField::new('mesa'),
            IntegerField::new('x'),
            IntegerField::new('y'),
        ];
    }
    
}
