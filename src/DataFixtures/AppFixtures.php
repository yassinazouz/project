<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Livres;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($j = 1; $j <= 3; $j++) {
            # remplir la table categorie

            $cat = new Categorie();
            $libelle = $faker->name();
            $cat->setLibelle($libelle)
                ->setSlug(strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $libelle)))
                ->setDescription($faker->sentence);
            $manager->persist($cat);
            for ($i = 1; $i < random_int(10, 15); $i++) {

                $livre = new Livres();
                $titre = $faker->name();
                $livre->setAuteur($faker->userName())
                    ->setDateEdition($faker->dateTime())
                    ->setTitre($titre)
                    ->setResume($faker->sentence(20))
                    ->setSlug(strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $titre)))
                    ->setPrix($faker->numberBetween(10, 300))
                    ->setQte($faker->numberBetween(0, 1000))
                    ->setEditeur($faker->company())
                    ->setISBN($faker->isbn13())
                    ->setImage($faker->imageUrl())
                    ->setCategorie($cat);
                $manager->persist($livre);
            }
            $manager->flush();
        }
    }
}
