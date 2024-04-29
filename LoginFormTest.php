<?php
require_once 'vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class LoginFormTest extends TestCase
{
    private $client;
    private $session;

    protected function setUp(): void
    {
        $this->session = new Session(new MockArraySessionStorage());
        $this->client = new Client([
            'base_uri' => 'http://localhost/TaskManager/',
            'cookies' => new CookieJar(),
            'http_errors' => false
        ]);
    }

    public function testLoginFormIncorrectCredentials()
    {
        // Simulate setting a session variable
        $this->session->set('authFailure', true);

        // Make a POST request to the form action
        $response = $this->client->post('backend/login.php', [
            'form_params' => [
                'email' => 'wrong@example.com',
                'password' => 'incorrectPassword'
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
       
    }

    public function testLoginFormCorrectCredentials()
    {
        $this->session->set('authFailure', true);

        $response = $this->client->post('backend/login.php', [
            'form_params' => [
                'email' => 'user@user.com',
                'password' => 'user'
            ]
        ]);
      

        
        $this->assertEquals(200, $response->getStatusCode());
    }
    public function testLoginFormCorrectAdminCredentials()
    {
        $response = $this->client->post('backend/login.php', [
            
                'email' => 'admin',
                'password' => 'admin'
            
        ]);

        echo ''. $response->getStatusCode() .'';
        echo $response->getHeaderLine('Location');
        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function tearDown(): void
    {
        $this->session->clear();
        session_abort();
    }
}
