<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Offres;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('ISBN')
            ->add('slug')
            ->add('auteur')
            ->add('image')
            ->add('resume')
            ->add('Editeur')
            ->add('dateEdition')
            ->add('prix')
            ->add('qte')
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
'choice_label' => 'libelle',
            ])
            ->add('Enregistrer',SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offres::class,
        ]);
    }
}
