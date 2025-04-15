<?php

namespace App\Service;

use App\Dto\PostDto;
use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\PostTagsRepository;
use App\Repository\TagRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;

class PostService
{
    public function __construct(
        private readonly PostRepository $postRepository,
        private readonly TagRepository $tagRepository,
        private readonly PostTagsRepository $postTagsRepository,
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function getAll(): array
    {
        $result = [];
        foreach ($this->postRepository->findAll() as $post) {
            $result[] = $this->formatAnswer($post);
        }

        return $result;
    }

    /**
     * @param int[] $tags
     * @return array
     */
    public function getAllByTags(array $tags): array
    {
        $result = [];
        foreach ($this->postRepository->getByTags($tags) as $post) {
            $result[] = $this->formatAnswer($post);
        }

        return $result;
    }

    /**
     * @throws ORMException
     * @throws Exception
     */
    public function create(PostDto $postDto): array
    {
        $this->em->getConnection()->beginTransaction();
        try {
            $post = $this->postRepository->create($postDto);
            foreach ($postDto->tags as $tagId) {
                if ($tag = $this->tagRepository->find($tagId)) {
                    $this->postTagsRepository->create($post, $tag);
                }
            }

            $this->em->refresh($post);
        } catch (\Exception $e) {
            $this->em->getConnection()->rollback();
            throw $e;
        }

        $this->em->getConnection()->commit();
        return $this->formatAnswer($post);
    }

    /**
     * @throws ORMException
     * @throws Exception
     */
    public function update(Post $post, PostDto $postDto): array
    {
        $this->em->getConnection()->beginTransaction();
        try {
            $post = $this->postRepository->update($post, $postDto);
            $tagIds = $postDto->tags;
            foreach ($post->getPostTags() as $postTag) {
                $tagId = $postTag->getTag()->getId();
                if (!in_array($tagId, $tagIds)) {
                    $this->postTagsRepository->delete($postTag);
                } else {
                    unset($tagIds[array_search($tagId, $tagIds)]);
                }
            }

            foreach ($tagIds as $tagId) {
                if ($tag = $this->tagRepository->find($tagId)) {
                    $this->postTagsRepository->create($post, $tag);
                }
            }

            $this->em->refresh($post);
        } catch (\Exception $e) {
            $this->em->getConnection()->rollback();
            throw $e;
        }

        $this->em->getConnection()->commit();
        return $this->formatAnswer($post);
    }


    /**
     * @throws ORMException
     * @throws Exception
     */
    public function delete(Post $post): void
    {
        $this->em->getConnection()->beginTransaction();
        try {
            foreach ($post->getPostTags() as $postTag) {
                $this->postTagsRepository->delete($postTag);
            }

            $this->postRepository->delete($post);
        } catch (\Exception $e) {
            $this->em->getConnection()->rollback();
            throw $e;
        }

        $this->em->getConnection()->commit();
    }

    public function formatAnswer(Post $post): array
    {
        $result = $post->asArray();
        $tags = [];
        foreach ($post->getPostTags() as $tag) {
            $tags[] = $tag->getTag()->asArray();
        }

        $result['tags'] = $tags;

        return $result;
    }
}