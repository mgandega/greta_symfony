<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Image;
use DateTimeImmutable;
use App\Entity\Categorie;
use App\Entity\Conference;
use App\Entity\Commentaire;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        // l'entité: categorie
        // l'entité: image
        // l'entité: conference
        // l'entité: commentaire

        $categories = ["conference sur Symfony", "conference sur Drupal", "conference sur Laravel"];
        for ($i = 1; $i <= 10; $i++) {
            $categorie = new Categorie();
            $categorie->setNom($faker->randomElement($categories));
            $manager->persist($categorie);
            $this->addReference('categorie' . $i, $categorie);
        }

        for ($i = 1; $i <= 10; $i++) {
            $image = new Image();
            // $fichier = "https://blog.1001salles.com/wp-content/uploads/2015/04/preparer-sa-salle.jpg";
            $fichier = $faker->image($dir = '/tmp', $width = 640, $height = 480);
            $infos = pathinfo($fichier);
            $nomFichier = $infos['basename'];
            $repertoire = 'public/uploads/images/' . $nomFichier;
            $url = 'uploads/images/' . $nomFichier;
            copy($fichier, $repertoire); // permet de copier un fichier dans un repertoire
            $image->setUrl($url);
            $image->setAlt($nomFichier);

            $file = new UploadedFile($repertoire, $nomFichier);
            $image->setFile($file);
            $manager->persist($image);
            $this->addReference('image' . $i, $image);
        }

        for ($i = 1; $i <= 10; $i++) {
            $conference = new Conference();
            $conference->setTitre($faker->name)
                ->setDescription($faker->text)
                ->setLieu($faker->city)
                ->setDate(new DateTimeImmutable())
                ->setCategorie($this->getReference('categorie'.$i))
                ->setImage($this->getReference('image' . $i))
                ->setFavorite(0);
            $manager->persist($conference);
            $this->addReference('conference' . $i, $conference);
        }

        for ($i = 1; $i <= 10; $i++) {
            $commentaire = new Commentaire;
            $commentaire->setPseudo($faker->title)
                ->setContent($faker->text)
                ->setPublishedAt(new DateTimeImmutable())
                ->setConference($this->getReference('conference' . $i));
            $manager->persist($commentaire);
        }

        $manager->flush();
    }
}
