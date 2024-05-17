<?php

namespace App\DataFixtures;

use DateTime;
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
        // l'entité: categorie
        // l'entité: image
        // l'entité: conference
        // l'entité: commentaire

        for ($i = 1; $i <= 10; $i++) {
            $categorie = new Categorie();
            $categorie->setNom('symfony' . $i);
            $manager->persist($categorie);
            $this->addReference('categorie' . $i, $categorie);
        }
        for ($i = 1; $i <= 10; $i++) {
            $image = new Image();
            $fichier = "https://blog.1001salles.com/wp-content/uploads/2015/04/preparer-sa-salle.jpg";
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
            $conference->setTitre('hello')
                ->setDescription('description')
                ->setLieu('paris')
                ->setDate(new DateTimeImmutable())
                ->setCategorie($this->getReference('categorie' . $i))
                ->setImage($this->getReference('image' . $i))
                ->setFavorite(0);
            $manager->persist($conference);
            $this->addReference('conference' . $i, $conference);
        }

        for($i=1; $i<=10; $i++){
            $commentaire = new Commentaire;
            $commentaire->setPseudo('martin')
            ->setContent('salut tout le monde')
            ->setPublishedAt(new DateTimeImmutable())
            ->setConference($this->getReference('conference' . $i));
            $manager->persist($commentaire);
        }

        $manager->flush();
    }
}
