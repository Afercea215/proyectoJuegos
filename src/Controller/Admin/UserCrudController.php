<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        if(Crud::PAGE_EDIT == $pageName || Crud::PAGE_NEW == $pageName){
            return [
                'email',
                'nombre',
                'apellidos',
                ChoiceField::new('roles')->setChoices(['ADMIN' => 'ROLE_ADMIN', 'USER' => 'ROLE_USER'])->allowMultipleChoices()->autocomplete(),
                ArrayField::new('roles'),
                
            ];
        }
        
        return [
            IdField::new('id'),
            EmailField::new('email'),
            TextField::new('nombre'),
            TextField::new('apellidos'),
            TextField::new('telegramUser'),
            ArrayField::new('roles'),
            //ArrayField::new('roles'),
            BooleanField::new('Admin')->setDisabled(),
        ];
    }
   
  /*   public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // call this method to focus the search input automatically when loading the 'index' page
            ->setAutofocusSearch()

            // defines the initial sorting applied to the list of entities
            // (user can later change this sorting by clicking on the table columns)
            ->setDefaultSort(['nombre' => 'DESC', 'apellidos' => 'DESC'])
            ->setDefaultSort(['seller.name' => 'ASC'])

            // the max number of entities to display per page
            ->setPaginatorPageSize(30)
            // the number of pages to display on each side of the current page
            // e.g. if num pages = 35, current page = 7 and you set ->setPaginatorRangeSize(4)
            // the paginator displays: [Previous]  1 ... 3  4  5  6  [7]  8  9  10  11 ... 35  [Next]
            // set this number to 0 to display a simple "< Previous | Next >" pager
            ->setPaginatorRangeSize(4)

            // these are advanced options related to Doctrine Pagination
            // (see https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/tutorials/pagination.html)
            ->setPaginatorUseOutputWalkers(true)
            ->setPaginatorFetchJoinCollection(true)
        ;
    } */
}
