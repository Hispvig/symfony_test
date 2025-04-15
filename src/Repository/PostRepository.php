<?php

namespace App\Repository;

use App\Dto\PostDto;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly EntityManagerInterface $em,
    )
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @param int[] $tagsIds
     * @return Post[]
     */
    public function getByTags(array $tagsIds): array
    {
        $tagsIds = array_values($tagsIds);
        $q = $this->createQueryBuilder('p');

        foreach ($tagsIds as $key => $tagId) {
            $q->innerJoin('p.postTags', 't' . $key)
                ->andWhere('t' . $key . '.tag = :id' . $key)
                ->setParameter(':id' . $key, $tagId);
        }
       return $q->getQuery()->getResult();
    }

    public function create(PostDto $postDto): Post
    {
        $post = new Post();
        $post->setTitle($postDto->title);
        $this->em->persist($post);
        $this->em->flush();

        return $post;
    }

    public function update(Post $post, PostDto $postDto): Post
    {
        $post->setTitle($postDto->title);
        $this->em->persist($post);
        $this->em->flush();

        return $post;
    }

    public function delete(Post $postTags): void
    {
        $this->em->remove($postTags);
        $this->em->flush();
    }
}
