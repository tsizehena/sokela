<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('tag')]
    private ?string $name = null;

    /**
     * @var Collection<int, Proverb>
     */
    #[ORM\ManyToMany(targetEntity: Proverb::class, mappedBy: 'tags')]
    private Collection $proverbs;


    public function __construct()
    {
        $this->proverbs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Proverb>
     */
    public function getProverbs(): Collection
    {
        return $this->proverbs;
    }

    public function addProverb(Proverb $proverb): static
    {
        if (!$this->proverbs->contains($proverb)) {
            $this->proverbs->add($proverb);
            $proverb->addTag($this);
        }

        return $this;
    }

    public function removeProverb(Proverb $proverb): static
    {
        if ($this->proverbs->removeElement($proverb)) {
            $proverb->removeTag($this);
        }

        return $this;
    }
}
