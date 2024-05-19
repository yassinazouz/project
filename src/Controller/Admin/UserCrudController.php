<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud (Crud $crud):Crud
    {
        return $crud
        ->setEntityLabelInPlural('Users')
        ->setEntityLabelInSingular('User')
        ->setPageTitle("index","- Administration de Users -")
        ->setPaginatorPageSize(10);
    
    }

    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('email'),
            TextField::new('password'),
            ArrayField::new('roles')

            
 
        ];
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
