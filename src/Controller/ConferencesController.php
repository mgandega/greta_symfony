<?php

namespace App\Controller;

use DateTime;
use Exception;
use App\AntiSpam;
use DateTimeImmutable;
use App\Entity\Categorie;
use App\Entity\Competence;
use App\Entity\Conference;
use App\Form\CompetenceType;
use App\Form\ConferenceType;
use Psr\Log\LoggerInterface;
use App\Events\SuppConferenceEvent;
use App\Events\AjoutConferenceEvent;
use App\Events\ModifConferenceEvent;
use App\Repository\CategorieRepository;
use App\Repository\ConferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ConferencesController extends AbstractController
{

    public $em;
    public static $compteur;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->em = $manager;
    }


    // le nom d'une route doit être unique
    // #[Route('/conferences/categorie/{nom}', name: 'conference.categorie')]
    #[Route('/', name: 'conference.index')]
    #[Route('/conferences/categorie/{id}', name: 'conference.categorie')]
    public function index(
        PaginatorInterface $paginator,
        LoggerInterface $logger,
        Request $request,
        CategorieRepository $categorie,
        EventDispatcherInterface $dispatcher
    ): Response {
        // dd(get_class_methods($logger));        // dd($request->attributes->get('id'));
        // $logger->info('juste une information');        // dd($request->attributes->get('id'));

        // $manager->getRepository(Conference::class)->findAll() permet de recuperer toutes les conférences
        // $request->attributes->get('id') est égale à $id 
        // on n'a pas mis de $id en parametre car on ne veut pas être obligé d'en mettre c'est le cas si on tape sur l'url /conferences du coup si on veut utiliser les deux routes (/conferences et /conferences/categorie/{id}) on prefere récupérer l'id par la methode $request->attributes->get('id')
        // if ($request->attributes->get('nom')) {
        if ($request->attributes->get('id')) {
            // $conferences = $this->em->getRepository(Conference::class)->findBy(['categorie' => $request->attributes->get('nom')]);
            // $conferences = $this->em->getRepository(Conference::class)->conferencesParCategorie($request->attributes->get('nom'));
            $conferences = $this->em->getRepository(Conference::class)->findBy(['categorie' => $request->attributes->get('id')]);
        } else {
            $conferences = $this->em->getRepository(Conference::class)->findAll();
        }


        // dd($lastConferences);
        // ici on retourne le template conferences.html.twig par rapport aux parametres passés en arguments
        // $categories = $categorie->findAll();
        $categories = $this->em->getRepository(Categorie::class)->findAll();
        // $categories = $this->em->getRepository(Categorie::class)->categorieUnique();
        // return $this->render("conferences/conferences.html.twig", ['conferences' => $conferences, 'categories' => $categories]);

        $conferences = $paginator->paginate(
            $conferences, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );


        return $this->render("conferences/conferences.html.twig", compact('conferences', 'categories'));
    }

    #[Route('/conference/details/{id}', name: 'conference.details', requirements: ['id' => '\d+'])]
    public function details($id)
    {
        // foreach ($this->conferences as $cle => $conference) {
        //     if ($conference['id'] == $id) {
        //         $conferences[] = $conference;
        //     }
        // }
        // je recupere la conference par rapport à son id (l'id qu'on lui a donné)
        $conference = $this->em->getRepository(Conference::class)->find($id); //query("select * from conference where id=$id");
        $categories = $this->em->getRepository(categorie::class)->findAll();

        return $this->render("conferences/conference.html.twig", ['conference' => $conference, 'categories' => $categories]);
    }

    #[Route('/conference/edit/{id}', name: 'conference.edit')]
    public function editer(Request $request, $id, EventDispatcherInterface $dispatcher)
    {

        $conference = $this->em->getRepository(Conference::class)->find($id);

        $form = $this->createForm(ConferenceType::class, $conference, ['button_label' => 'Modifier une conférence']);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isvalid()) {

            $conference = $form->getData();
            $dossier_images = $_SERVER['DOCUMENT_ROOT'] . "/uploads/images";
            // dd($request->server['DOCUMENT_ROOT']);
            // dd($form->getData()->getImage()->getFile()->getClientOriginalName());
            if ($conference->getImage()->getFile() instanceof UploadedFile) {
                $filename = rand(1000, 9999) . time() . '_' . $form->getData()->getImage()->getFile()->getClientOriginalName();
                // dd(get_class_methods($form->getData()->getImage()->getFile()));
                $bddFile = "uploads/images";
                $file = $conference->getImage()->getFile();
                $file->move($dossier_images, $filename);
                $conference->getImage()->setUrl($bddFile . '/' . $filename);
                $conference->getImage()->setAlt($conference->getImage()->getFile()->getClientOriginalName());
                $conference->getImage()->setFile($conference->getImage()->getFile());
                $chemin = $bddFile . '/' . $filename;
            }
            $conference->setUser($this->getUser());
            // on utilise seulement le flush() si on veut supprimer ou modifier
            // si on utilise persist() et flush(), on rajoutera une autre ligne dans la table
            $this->em->flush();


            $event = new ModifConferenceEvent($conference, $this->getuser());
            $dispatcher->dispatch($event);
            $this->addFlash('success', 'Conference modifiée avec succès');
            return $this->redirectToRoute('conference.index');
        }
        return $this->render("conferences/edit.html.twig", ['conference' => $conference, 'form' => $form->createView(), 'bouton' => 'modifier']);
    }
    #[Route('/conference/supp/{id}', name: 'conference.supp')]
    public function delete($id, EventDispatcherInterface $dispatcher)
    {
        $conference = $this->em->getRepository(Conference::class)->find($id);
        $deletedConference = $conference;
        $this->em->remove($conference); // pour supprimer
        $this->em->flush();

        $event = new SuppConferenceEvent($deletedConference, $this->getUser());
        $dispatcher->dispatch($event);
        // SuppConferenceEvent
        $this->addFlash('failure', 'conference supprimée avec succès');
        return $this->redirectToRoute('conference.index');
    }

    #[Route('/conference/add', name: 'conference.add')]
    public function add(
        Request $request,
        ValidatorInterface $validator,
        AntiSpam $antiSpam,
        EventDispatcherInterface $dispatcher
    ) {
        // en mettant 'validation_groups'=>'create', on est obligé de respecter la contrainte de validation
        $conference = new Conference();
        $form = $this->createForm(ConferenceType::class, $conference, ['button_label' => 'Ajouter une conférence', 'validation_groups' => 'create']);
        // ici je lie les données du formulaire avec l'objet conference s'il y'en a
        // il hydrate les propriétés
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {

            // if ($antiSpam->alert($form->get('description')->getData())) {
            //     throw new Exception('le message est considéré comme un spam');
            // }

            $dossier_images = $_SERVER['DOCUMENT_ROOT'] . "uploads/images";
            // dd($request->server['DOCUMENT_ROOT']);
            // dd($form->getData()->getImage()->getFile()->getClientOriginalName());

            $filename = rand(1000, 9999) . time() . '_' . $form->getData()->getImage()->getFile()->getClientOriginalName();
            // dd(get_class_methods($form->getData()->getImage()->getFile()));

            $objectFile = $form->getData()->getImage()->getFile();
            $bddFile = "uploads/images";
            $objectFile->move($dossier_images, $filename);
            $conference->getImage()->setUrl($bddFile . '/' . $filename);
            $conference->getImage()->setAlt($filename);
            $conference->getImage()->setFile($objectFile);

            $conference->setUser($this->getUser());


            $time = time();

            $session = $request->getSession();
            $date = $session->get('date');
            if (isset($date) and $time - $date > 30) {
                $session->set('date', $time);

                // on vérifie si le contenu du message contient un mot interdit
                $description = $conference->getDescription();
                $tab = explode(' ', $description); // on transforme la description en tableau où chaque mot de la descriptoin sera un élément du tableau ($tab)
                $motsInterdits = ["abandon", "demotivation"];
                // on texte si les mots abandon et demotivation se trouve dans le tableau ($tab)
                foreach ($motsInterdits as $mot) {
                    if (in_array($mot, $tab)) {
                        // throw new InvalidArgumentException("la description contient au moin un mot interdit");
                        throw new InvalidArgumentException("la description contient au moin un mot interdit");
                    }
                }
                // fin vérification

                $this->em->persist($conference);
                $this->em->flush();
            } else {
                if ($date) {
                    $session->set('date', $time);
                    echo "passage de moin de 30 secondes";
                } else {
                    $session->set('date', $time);
                    echo "premier passage";
                }
            }

            $event = new AjoutConferenceEvent($conference, $this->getUser());
            $dispatcher->dispatch($event);

            return $this->redirectToRoute("conference.index");
        }

        return $this->render("conferences/ajout.html.twig", ['form' => $form->createView(), 'bouton' => 'ajouter']);
    }
    #[Route('/conferences/menu', name: 'conference.menu')]
    public function menu()
    {
        $categories = $this->em->getRepository(Categorie::class)->findAll();
        $conferences = $this->em->getRepository(Conference::class)->findAll(); // $pdo->query("select * from conference");
        $lastConferences = $this->em->getRepository(Conference::class)->mes5dernieresConferences();

        return $this->render("conferences/menu.html.twig", ['conferences' => $conferences, 'categories' => $categories, 'lastConferences' => $lastConferences]);
    }
    #[Route('/teste', name: 'teste.length')]
    public function teste(Request $request)
    {
        // dd($request->query->get('nb'));
        $nb = $request->request->get('nb');
        if ($nb) {
            $conferences = $this->em->getRepository(Conference::class)->findByTitleLength($nb);
            return $this->render("conferences/conferences.html.twig", ['conferences' => $conferences]);
        }
        return $this->render("conferences/query.html.twig");
    }
    #[Route('/favorite:{id}', name: 'favorite')]
    public function favorite($id, Conference $conference)
    {
        if ($conference->getFavorite() > 0) {
            $compteur = $conference->getFavorite() + 1;
            $conference->setFavorite(0);
        } else {
            $conference->setFavorite(1);
        }
        $this->em->flush();

        return $this->redirectToRoute('conference.details', ['id' => $id]);
    }

    #[Route('/conference/recherche/date', name: 'conference.recherche')]
    public function recherche(Request $request)
    {
        $date = $request->query->get('date'); //$_POST['date']


        if ($request->query->get('date')) {
            $conferences = $this->em->getRepository(Conference::class)->conferenceParDate($date);
        } elseif ($request->attributes->get('id')) {
            // $conferences = $this->em->getRepository(Conference::class)->findBy(['categorie' => $request->attributes->get('nom')]);
            // $conferences = $this->em->getRepository(Conference::class)->conferencesParCategorie($request->attributes->get('nom'));
            $conferences = $this->em->getRepository(Conference::class)->findBy(['categorie' => $request->attributes->get('id')]);
        } else {
            $conferences = $this->em->getRepository(Conference::class)->findAll();
        }
        $categories = $this->em->getRepository(Categorie::class)->findAll();
        // $categories = $this->em->getRepository(Categorie::class)->categorieUnique();
        // return $this->render("conferences/conferences.html.twig", ['conferences' => $conferences, 'categories' => $categories]);
        return $this->render("conferences/conferences.html.twig", compact('conferences', 'categories'));
        // dd($conferences);

    }

    #[Route('competence/add', name: 'conference.ajoutCompetence')]
    public function ajoutCompetence(request $request)
    {

        $competence = new Competence();

        $form = $this->createForm(CompetenceType::class, $competence);
        $form->handleRequest($request);
        // si le formulaire est soumis et est valide
        if ($form->isSubmitted() && $form->isvalid()) {

            $this->em->persist($competence);
            $this->em->flush();
            return $this->redirectToRoute('conference.index');
        }
        return $this->render("conferences/ajoutCompetence.html.twig", ["form" => $form->createView()]);
    }

    // #[Route('conferences/filtre', name: 'conferences.filtres')]
    // public function filtre(Request $request, PaginatorInterface $paginator)
    // {

    //     // récupération des inputs

    //     // mis en session des données
    //     $session = $request->getSession();
    //     // si on clique sur submit
    //     if ($request->isMethod("POST")) {
    //         $date = !empty($request->request->get('dateRecherche')) ?$request->request->get('dateRecherche'): (new DateTime())->format("Y-m-d");
    //         $prix = !empty($request->request->get('prix')) ?$request->request->get('prix'): null;
    //         $categorie = !empty($request->request->get('categorie')) ?$request->request->get('categorie'): null;
    //         $session->set('dateRecherche', $date);
    //         $session->set('prix', $prix);
    //         $session->set('categorie', $categorie);
    //         // dd($date,$prix,$categorie);
    //     } elseif ($request->query->get('page')) {
    //         $date = $session->get('dateRecherche');
    //         $prix = $session->get('prix');
    //         $categorie = $session->get('categorie');
    //     } elseif ($request->isMethod("GET")) {
    //         $session->remove('dateRecherche');
    //         $session->remove('prix');
    //         $session->remove('categorie');
    //         $date = null;
    //         $prix = null;
    //         $categorie = null;
    //     }

    //     $conferences = $this->em->getRepository(Conference::class)->recherche($date,$prix, $categorie);
    //     $categories = $this->em->getRepository(Categorie::class)->findAll();

    //     dd($conferences->getResult());
    //     $paginator = $paginator->paginate(
    //         $conferences, /* query NOT result */
    //         $request->query->getInt('page', 1), /*page number*/
    //         6 /*limit per page*/
    //     );
    //     return $this->render("conferences/conferences.html.twig", [
    //         "conferences" => $paginator, 
    //         "categories" => $categories,
    //         "selectedCategorie" => $categorie,
    //         "selectedPrix"=>$prix,
    //         "selectedDate"=>$date
    //     ]);
    // }
    #[Route('/conferences/recherche', name: 'conferences.filtres')]
    public function filtre(Request $request, PaginatorInterface $paginator)
    {
        $session = $request->getSession();

        if ($request->isMethod("POST")) {
            $date = !empty($request->request->get('date')) ? $request->request->get('date') : null;
            $prix = !empty($request->request->get('prix')) ? $request->request->get('prix') : null;
            $categorie = !empty($request->request->get('categorie')) ? $request->request->get('categorie') : null;
            $session->set('dateRecherche', $date);
            $session->set('prix', $prix);
            $session->set('categorie', $categorie);
        } elseif ($request->query->get('page')) {
            $date = $session->get('dateRecherche');
            $prix = $session->get('prix');
            $categorie = $session->get('categorie');
        } elseif ($request->isMethod("GET")) {
            $session->remove('dateRecherche');
            $session->remove('prix');
            $session->remove('categorie');
            $date = null;
            $prix = null;
            $categorie = null;
        }


        $categories = $this->em->getRepository(Categorie::class)->findAll();
        // $lastConferences = $this->em->getRepository(Conference::class)->lastConferences();

        // dd($date,$prix,$categorie);
        $conf = $this->em->getRepository(Conference::class)->recherche($date, $prix, $categorie);

        // dd($conf);

        // dd($conf);
        // $resultatConferences = $conferences->getResult();
        $conferences = $paginator->paginate(
            $conf, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );
        // dd($date);
        // $conferences = $pagination->getItems();
        // $val ='hello';
        return $this->render("conferences/conferences.html.twig", [
            'selectedCategorie' => $categorie,
            'selectedPrix' => $prix,
            'selectedDate' => $date,
            'categories' => $categories,
            'conferences' => $conferences,
            // 'lastConferences' => $lastConferences
        ]);
        // return $this->render('conferences/conf.html.twig', []);
    }
}
