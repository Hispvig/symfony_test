<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\PostTags;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostTags>
 */
class PostTagsRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly EntityManagerInterface $em,
    )
    {
        parent::__construct($registry, PostTags::class);
    }

    public function create(Post $post, Tag $tag): PostTags
    {
        $postTags = new PostTags();
        $postTags->setPost($post);
        $postTags->setTag($tag);
        $this->em->persist($postTags);
        $this->em->flush();

        return $postTags;
    }

    public function delete(PostTags $postTags): void
    {
        $this->em->remove($postTags);
        $this->em->flush();
    }
}
