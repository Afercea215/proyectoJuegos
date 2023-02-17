<?php

namespace App\Controller\Admin;

use App\Entity\Reserva;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\AST\WhereClause;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;

class ReservaCrudController extends AbstractCrudController
{
    //private $ac;

    public static function getEntityFqcn(): string
    {
        return Reserva::class;
    }

/*     public function __construct(AdminContext $ac)
    {
        $this->ac=$ac;
    } */

    
    public function configureFields(string $pageName): iterable
    {
        //dd($this->get(AdminContextProvider::class)->getContext()->getEntity()->getInstance());
        //dd($this->ac);
        return [
            IdField::new('id'),
            DateField::new('fecha'),
            DateField::new('fechaAnul'),
            BooleanField::new('presentado'),
            AssociationField::new('tramo'),
            AssociationField::new('user')
                ->setTextAlign('right'),
            AssociationField::new('juego'),
            AssociationField::new('mesa')
            //->setQueryBuilder(function (QueryBuilder $qb, Reserva $r=new Reserva()){
                //$qb->andWhere('entity.id = '.$this->);
           // })
           ,
        ];
    }
}
