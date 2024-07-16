<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use DateTimeImmutable;
use App\Entity\Categorie;
use App\Entity\Competence;
use App\Entity\Conference;
use App\Entity\Commentaire;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(public UserPasswordHasherInterface $passwordHasher){}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        // l'entité: categorie
        // l'entité: image
        // l'entité: conference
        // l'entité: commentaire

        $categories = ["conference sur Symfony", "conference sur Drupal", "conference sur Laravel"];
        $utilisateur = [];
        $cat = [];
        for ($i = 0; $i <3 ; $i++) {
            $categorie = new Categorie();
            $categorie->setNom($categories[$i]);
            $manager->persist($categorie);
            $cat[] = $categorie;
        }

        $competences = ['html/css','php','symfony','laravel','python'];
        $com = [];
        for ($i = 0; $i <5 ; $i++) {
            $competence = new Competence();
            $competence->setNom($competences[$i]);
            $manager->persist($competence);
            $com[] = $competence;
        }

        for($i=0; $i<10; $i++){
            $user = new User();
            $user->setEmail($faker->email())
           ->setFirstName($faker->name)
           ->setLastName($faker->name);
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                'blablabla'
                // $faker->name
            );
            $user->setPassword($hashedPassword);
            $user->setRoles($faker->randomElement([["ROLE_ADMIN"],["ROLE_USER"]]))
            ->setTelephone($faker->phoneNumber());
            $manager->persist($user);
            $utilisateur[] = $user;
            
        }

        for ($i = 1; $i <= 10; $i++) {
            $image = new Image();
            $fichier = "https://blog.1001salles.com/wp-content/uploads/2015/04/preparer-sa-salle.jpg";
            // $fichier = $faker->image($dir = '/tmp', $width = 640, $height = 480);
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
                ->setPrix($faker->numberBetween(40, 400))
                ->setDate(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('now','+1 years')) )
                // ->setDate($faker->dateTimeThisYear())
                ->setCategorie($faker->randomElement($cat))
                ->setImage($this->getReference('image' . $i));
                foreach($com as $c){
                    $conference->addCompetence($c);
                }
                $conference->setFavorite(0);
                $conference->setUser($faker->randomElement($utilisateur));
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
