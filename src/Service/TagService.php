<?php

namespace App\Service;

use App\Dto\TagDto;
use App\Entity\Tag;
use App\Repository\TagRepository;

class TagService
{
    public function __construct(
        private readonly TagRepository $tagRepository,
    ) {
    }

    public function getAll(): array
    {
        $result = [];
        foreach ($this->tagRepository->findAll() as $tag) {
            $result[] = $tag->asArray();
        }

        return $result;
    }

    public function create(TagDto $tagDto): array
    {
        $tag = $this->tagRepository->create($tagDto);
        return $tag->asArray();
    }

    public function update(Tag $tag, TagDto $tagDto): array
    {
        $tag = $this->tagRepository->update($tag, $tagDto);
        return $tag->asArray();
    }
}