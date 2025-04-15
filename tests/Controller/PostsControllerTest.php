<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class PostsControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/posts', content: "{}");

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertJson(
            "[]",
        );
    }

    public function testCreate(): void
    {
        $content = json_encode([
            'title' => 'Tets',
            'tags' => [],
        ]);
        $client = static::createClient();
        $client->request('GET', '/api/posts', content: $content);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertJson($content);
    }
}
