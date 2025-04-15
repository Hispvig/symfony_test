<?php

namespace App\Repository;

use App\Dto\TagDto;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct($registry, Tag::class);
    }

    public function create(TagDto $tagDto): Tag
    {
        $tag = new Tag();
        $tag->setName($tagDto->name);
        $this->em->persist($tag);
        $this->em->flush();

        return $tag;
    }

    public function update(Tag $tag, TagDto $tagDto): Tag
    {
        $tag->setName($tagDto->name);
        $this->em->persist($tag);
        $this->em->flush();

        return $tag;
    }
}
