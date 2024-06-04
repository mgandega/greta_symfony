<?php

namespace App\Entity;

use App\Repository\ConferenceRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[Assert\EnableAutoMapping]
#[ORM\Entity(repositoryClass: ConferenceRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Conference
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Length(
        min: 5,
        max: 50,
        minMessage: 'le titre doit être au minimum de {{ limit }} caractères',
        maxMessage: 'le titre doit être au maximum de {{ limit }} caractères ',
    )]
    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[Assert\NotBlank()]
    #[ORM\ManyToOne(inversedBy: 'conferences')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Categorie $categorie = null;

    #[Assert\Valid()]
    #[ORM\OneToOne(inversedBy: 'conference', cascade: ['persist', 'remove'])]
    private ?Image $image = null;

    /**
     * @var Collection<int, Commentaire>
     */
    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'conference')]
    private Collection $commentaires;

    #[ORM\Column(nullable: true)]
    private ?int $favorite = null;

    #[ORM\ManyToOne(inversedBy: 'conference')]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbReservations = 0;

    /**
     * @var Collection<int, Competence>
     */
    #[ORM\ManyToMany(targetEntity: Competence::class, inversedBy: 'conferences')]
    private Collection $competence;


    public function __construct()
    {
        $this->date = new DateTimeImmutable();
        $this->commentaires = new ArrayCollection();
        $this->competence = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setConference($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getConference() === $this) {
                $commentaire->setConference(null);
            }
        }

        return $this;
    }

    public function getFavorite(): ?int
    {
        return $this->favorite;
    }

    public function setFavorite(?int $favorite): static
    {
        $this->favorite = $favorite;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PreUpdate]
    public function updatedAt(): static
    {
        $this->updatedAt = new DateTimeImmutable();
        return $this;
    }

    public function getNbReservations(): ?int
    {
        return $this->nbReservations;
    }

    public function setNbReservations(?int $nbReservations): static
    {
        $this->nbReservations = $nbReservations;

        return $this;
    }

    public function incremente():void
    {
        $this->nbReservations++;
        // $this->nbReservations = $this->nbReservations + 1;
    }

    /**
     * @return Collection<int, Competence>
     */
    public function getCompetence(): Collection
    {
        return $this->competence;
    }

    public function addCompetence(Competence $competence): static
    {
        if (!$this->competence->contains($competence)) {
            $this->competence->add($competence);
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): static
    {
        $this->competence->removeElement($competence);

        return $this;
    }
}
