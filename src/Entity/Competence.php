<?php

namespace App\Entity;

use App\Repository\CompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompetenceRepository::class)]
class Competence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * @var Collection<int, Conference>
     */
    #[ORM\ManyToMany(targetEntity: Conference::class, mappedBy: 'competence')]
    private Collection $conferences;

    public function __construct()
    {
        $this->conferences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Conference>
     */
    public function getConferences(): Collection
    {
        return $this->conferences;
    }

    public function addConference(Conference $conference): static
    {
        if (!$this->conferences->contains($conference)) {
            $this->conferences->add($conference);
            $conference->addCompetence($this);
        }

        return $this;
    }

    public function removeConference(Conference $conference): static
    {
        if ($this->conferences->removeElement($conference)) {
            $conference->removeCompetence($this);
        }

        return $this;
    }
}
