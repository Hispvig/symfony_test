<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @var Collection<int, PostTags>
     */
    #[ORM\OneToMany(targetEntity: PostTags::class, mappedBy: 'post', orphanRemoval: true)]
    private Collection $postTags;

    public function __construct()
    {
        $this->postTags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, PostTags>
     */
    public function getPostTags(): Collection
    {
        return $this->postTags;
    }

    public function addPostTag(PostTags $postTag): static
    {
        if (!$this->postTags->contains($postTag)) {
            $this->postTags->add($postTag);
            $postTag->setPost($this);
        }

        return $this;
    }

    public function removePostTag(PostTags $postTag): static
    {
        if ($this->postTags->removeElement($postTag)) {
            // set the owning side to null (unless already changed)
            if ($postTag->getPost() === $this) {
                $postTag->setPost(null);
            }
        }

        return $this;
    }

    public function asArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
        ];
    }
}
