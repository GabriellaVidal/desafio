<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function testSendPixWithJWT()
    {
        $data = [
            'payee' => "exemple@example.com",
            'value' => 100,
        ];

        $response = $this->json('POST', '/api/pix', $data);
        $response->assertStatus(401);
//        $response->assertJson(['message' => "UNAUTHORIZED"]);
    }
}
