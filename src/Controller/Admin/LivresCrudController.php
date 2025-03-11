<?php

namespace App\Controller\Admin;

use App\Entity\Livres;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LivresCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Livres::class;
    }

    public function configureCrud (Crud $crud):Crud
    {
        return $crud
        ->setEntityLabelInPlural('Livres')
        ->setEntityLabelInSingular('Livre')
        ->setPageTitle("index","- Administration des Offres -")
        ->setPaginatorPageSize(10);
        

    }

    
    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('titre'),
            TextField::new('ISBN'),
            TextField::new('slug'),
            TextField::new('auteur'),
            TextField::new('image'),
            TextEditorField::new('resume'),
            TextField::new('editeur'),
            DateField::new('dateEdition'),
            MoneyField::new('prix')->setCurrency('TND'),
            NumberField::new('qte')
        ];
    }
    
}
