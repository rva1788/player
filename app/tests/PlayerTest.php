<?php

namespace App\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
    private const URL = "http://testing_nginx";

    /**
     * @return void
     * @throws GuzzleException
     */
    public function testAdd(): void
    {
        global $lastId;

        $client = new Client([
            'base_uri' => self::URL,
        ]);

        $response = $client->post('player', [
            'debug' => TRUE,
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => '{"name":"New Player Test","country":"Bulgaria","birth_date":"1990-04-22","position": "midfielder"}'
        ]);

        $content = $response->getBody()->getContents();
        $data = json_decode($content, true);

        $lastId = $data['data']['id'];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('result', $data);
        $this->assertTrue($data['result']);
    }

    /**
     * @depends testAdd
     * @return void
     * @throws GuzzleException
     */
    public function testEdit(): void
    {
        global $lastId;

        $client = new Client([
            'base_uri' => self::URL,
            'timeout' => 5.0,
        ]);

        $response = $client->put('player/' . $lastId, [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => '{"name":"New Player Test 2"}'
        ]);

        $content = $response->getBody()->getContents();
        $data = json_decode($content, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('result', $data);
        $this->assertTrue($data['result']);
    }

    /**
     * @depends testAdd
     * @return void
     * @throws GuzzleException
     */
    public function testDelete(): void
    {
        global $lastId;

        $client = new Client([
            'base_uri' => self::URL,
            'timeout' => 5.0,
        ]);

        $response = $client->delete('player/' . $lastId, [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
        ]);

        $content = $response->getBody()->getContents();
        $data = json_decode($content, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('result', $data);
        $this->assertTrue($data['result']);
    }
}
