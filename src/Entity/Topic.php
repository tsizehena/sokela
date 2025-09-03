<?php

namespace App\Entity;

use App\Repository\TopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TopicRepository::class)]
class Topic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('topic')]
    private ?string $label = null;

    /**
     * @var Collection<int, Proverb>
     */
    #[ORM\OneToMany(targetEntity: Proverb::class, mappedBy: 'topic')]
    private Collection $proverbs;

    public function __construct()
    {
        $this->proverbs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

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
            $proverb->setTopic($this);
        }

        return $this;
    }

    public function removeProverb(Proverb $proverb): static
    {
        if ($this->proverbs->removeElement($proverb)) {
            // set the owning side to null (unless already changed)
            if ($proverb->getTopic() === $this) {
                $proverb->setTopic(null);
            }
        }

        return $this;
    }
}
