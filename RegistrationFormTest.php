<?php
require_once 'vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class RegistrationFormTest extends TestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = new Client([
            'base_uri' => 'http://localhost/TaskManager/',
            'cookies' => new CookieJar(),
            'http_errors' => false
        ]);
    }

    public function testRegistrationSuccess()
    {
        $response = $this->client->post('backend/register.php', [
            'form_params' => [
                'username' => 'newuser',
                'email' => 'newuser@example.com',
                'password' => 'securePassword',
                'role' => '0'
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRegistrationFailure()
    {
        $response = $this->client->post('backend/register.php', [
            'form_params' => [
                'username' => '',
                'email' => 'invalid-email',
                'password' => '123',
                'role' => '0'
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function tearDown(): void
    {
    }
}

