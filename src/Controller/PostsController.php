<?php

namespace App\Controller;

use App\Dto\PostDto;
use App\Entity\Post;
use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class PostsController extends AbstractController
{
    public function __construct(
        private readonly PostService $postService,
    ) {
    }

    #[Route('/api/posts', name: 'app_posts', methods: ['GET'], format: 'json')]
    public function index(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $tags = [];
        if(isset($data['tags']) && is_array($data['tags'])){
            $tags = $data['tags'];
        }

        if($tags){
            $result = $this->postService->getAllByTags($tags);
        } else {
            $result = $this->postService->getAll();
        }

        return $this->json([
            'posts' => $result,
        ]);
    }

    #[Route('/api/post/{post}', name: 'app_post_get', methods: ['GET'], format: 'json')]
    public function getPost(Post $post): JsonResponse {
        return $this->json([
            'post' => $this->postService->formatAnswer($post),
        ]);
    }

    #[Route('/api/post', name: 'app_post_add', methods: ['POST'], format: 'json')]
    public function create(
        #[MapRequestPayload(acceptFormat: 'json')] PostDto $postDto,
    ): JsonResponse {
        return $this->json([
            'post' => $this->postService->create($postDto),
        ]);
    }

    #[Route('/api/post/{post}', name: 'app_post_edit', methods: ['PUT'], format: 'json')]
    public function update(
        Post $post,
        #[MapRequestPayload(acceptFormat: 'json')] PostDto $postDto,
    ): JsonResponse {
        return $this->json([
            'post' => $this->postService->update($post, $postDto),
        ]);
    }

    #[Route('/api/post/{post}', name: 'app_post_delete', methods: ['DELETE'], format: 'json')]
    public function delete(Post $post): JsonResponse {
        $this->postService->delete($post);
        return $this->json([]);
    }
}
