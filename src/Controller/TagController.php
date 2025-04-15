<?php

namespace App\Controller;

use App\Dto\TagDto;
use App\Entity\Tag;
use App\Service\TagService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class TagController extends AbstractController
{
    public function __construct(
        private readonly TagService $tagService,
    ) {
    }

    #[Route('/api/tags', name: 'app_tags', methods: ['GET'], format: 'json')]
    public function index(): JsonResponse
    {
        return $this->json([
            'tags' => $this->tagService->getAll(),
        ]);
    }

    #[Route('/api/tag', name: 'app_tag_add', methods: ['POST'], format: 'json')]
    public function create(
        #[MapRequestPayload(acceptFormat: 'json')] TagDto $tagDto,
    ): JsonResponse {
        return $this->json([
            'post' => $this->tagService->create($tagDto),
        ]);
    }

    #[Route('/api/tag/{tag}', name: 'app_tag_edit', methods: ['PUT'], format: 'json')]
    public function update(
        Tag $tag,
        #[MapRequestPayload(acceptFormat: 'json')] TagDto $tagDto,
    ): JsonResponse {
        return $this->json([
            'post' => $this->tagService->update($tag, $tagDto),
        ]);
    }
}
